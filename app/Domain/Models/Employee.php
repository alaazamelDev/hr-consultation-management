<?php

namespace App\Domain\Models;

use App\Application\Http\Resources\ActionResource;
use App\Application\Http\Resources\AffectedUserResource;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;

/**
 * @property mixed current_job_title
 */
class Employee extends Model
{
    use HasFactory, SoftDeletes;

    protected $primaryKey = 'emp_id';
    protected $fillable = [
        'user_id',
        'emp_data_id',
        'job_app_id',
        'start_date',
        'schedule_id',
        'cur_title',
        'cur_dep'
    ];


    public function leaves(): HasMany
    {
        return $this->hasMany(Leave::class, 'emp_id', 'emp_id');
    }

    public function lateTimes(): HasMany
    {
        return $this->hasMany(Latetime::class, 'emp_id', 'emp_id');
    }

    public function overtimes(): HasMany
    {
        return $this->hasMany(Overtime::class, 'emp_id', 'emp_id');
    }

    public function shiftRequests(): HasMany
    {
        return $this->hasMany(ShiftRequest::class, 'emp_id', 'emp_id');
    }

    public function attendances(): HasMany
    {
        return $this->hasMany(Attendance::class, 'emp_id', 'emp_id');
    }

    public function employmentStatuses(): BelongsToMany
    {
        return $this->belongsToMany(
            EmploymentStatus::class,
            'employee_statuses',
//            'emp_id',
//            'emp_status_id',
            'emp_id',
            'emp_status_id'
        )->withPivot('start_date', 'end_date');
    }


    public function schedule(): BelongsTo
    {
        return $this->belongsTo(
            Schedule::class,
            'schedule_id',
            'schedule_id'
        )->withTrashed();
    }

    public function staffings(): HasMany
    {
        return $this->hasMany(Staffing::class, 'emp_id', 'emp_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    public function jobApplication(): BelongsTo
    {
        return $this->belongsTo(JobApplication::class, 'job_app_id', 'job_app_id');
    }

    public function absences(): HasMany
    {
        return $this->hasMany(Absence::class, 'emp_id', 'emp_id');
    }

    /**
     * Get Current Employment Status Mutator.
     *
     * the employment status is the current status of the employee
     * is the record in the pivot table employee_statuses, where the end_date is null
     * and the start_date is the max start_date.
     */
    public function getCurrentEmploymentStatusAttribute(): Model|BelongsTo|null
    {
        return $this->employmentStatuses()
            ->whereNull('end_date')
            ->orderByDesc('start_date')
            ->first();
    }


    // KEEP THIS

    /**
     * Get Current Department Mutator.
     * this function is used to get the current department of the employee
     * by checking the end_date of the staffing record
     */
    public function getCurrentDepartmentAttribute(): Model|BelongsTo|null
    {
        // if there is no staffing record with null end_date
        // then the employee is not working in any department
        if ($this->staffings()->whereNull('end_date')->doesntExist()) {
            return null;
        }

        return $this->staffings()->whereNull('end_date')->first()->department;
    }


    // KEEP THIS

    /**
     * Get Current Job Title Mutator.
     * this function is used to get the current job title of the employee
     * by checking the end_date of the staffing record
     */
    public function getCurrentJobTitleAttribute(): Model|BelongsTo|null
    {
        // if there is no staffing record with null end_date
        // then the employee does not have a job title
        if ($this->staffings()->whereNull('end_date')->doesntExist()) {
            return null;
        }
        return $this->staffings()->whereNull('end_date')->first()->jobTitle;
    }

    /**
     * Get Start Working date Mutator.
     *
     * this represents the start date of the first
     * staffing record of the employee
     */
    public function getStartWorkingDateAttribute(): string|null
    {
        // if employee doesn't have any staffing records
        // then he didn't start working yet
        if (!$this->staffings()->exists()) {
            return null;
        }
        return $this->staffings()->orderBy('start_date')->first()->start_date;
    }

    public function vacations(): HasMany
    {
        return $this->hasMany(EmployeeVacation::class, 'emp_id', 'emp_id');
    }


    /**
     * Get Employee Full Name Mutator.
     */
    public function getFullNameAttribute(): string
    {
        $jobApp = $this->jobApplication;
        if (isset($jobApp)) {
            $empData = $jobApp->empData;

            if (isset($empData)) {
                // extract the first name
                $firstName = $empData->first_name;
                // extract the last name
                $lastName = $empData->last_name;

                // return the full name
                return $firstName . ' ' . $lastName;
            }
        }

        return '';
    }

    public function getFirstNameAttribute(): string
    {
        $jobApp = $this->jobApplication;
        if (isset($jobApp)) {
            $empData = $jobApp->empData;

            if (isset($empData)) {
                // extract the first name
                return $empData->first_name;
            }
        }

        return '';
    }

    public function getLastNameAttribute(): string
    {
        $jobApp = $this->jobApplication;
        if (isset($jobApp)) {
            $empData = $jobApp->empData;

            if (isset($empData)) {
                // extract the last name
                return $empData->last_name;
            }
        }

        return '';
    }


    // Keep this
    public function empData(): Model|BelongsTo|null
    {
        return $this->jobApplication()->first()->empData();
    }


    /**
     * Mutator to fetch all the permissions of the employee
     * either the permissions given because of his job title
     * or the permissions given to him directly (stored in staffingPermissions)
     * with status = 1, and excluded the permissions with status = 0
     */
    public function getPermissionsAttribute(): Collection
    {
        // get the permissions given to the employee because of his job title
        $jobTitlePermissions = $this->staffings()
            ->whereNull('end_date')
            ->latest()
            ->first()
            ->jobTitle
            ->permissions;

        // get the permissions given to the employee directly
        $staffingPermissions = $this->staffings()
            ->whereNull('end_date')
            ->latest()
            ->first()
            ->permissions;

        // merge the two collections
        // add attribute called type = "default" to the job title permissions
        // add attribute called type = "granted" to the staffing permissions
        // with status = 1 & type = "excluded" with status = 0
        $permissions = array();

        foreach ($staffingPermissions as $permission) {
            if ($permission->pivot->status == 0) {
                $permission->type = "excluded";
            } else {
                $permission->type = "granted";
            }
            $permissions[] = $permission;
        }

        foreach ($jobTitlePermissions as $permission) {

            // check if any of the permissions array objects contains
            // a permission with the same permission_id
            // if so, then the permission is already added
            // so we don't need to add it again
            if (in_array($permission, $permissions)) {
                continue;
            }

            $permission->type = "default";
            $permissions[] = $permission;
        }


        return collect($permissions);
    }

    /**
     * Mutator to fetch the job title history of the employee
     * each consists of job title name, start date, end date
     * and append the current job title to the end of the collection
     */
    public function getJobTitleHistoryAttribute(): Collection
    {

        // get the job title history of the employee
        // and check if the same job title is repeated
        // one after the other, that means that the job title
        // of the employee didn't change, so we don't need to
        // add it to the history
        $jobTitleHistory = $this->staffings()
            ->whereNotNull('end_date')
            ->orderByDesc('start_date')
            ->get()
            ->map(function ($staffing) {
                return [
                    'job_title_id' => $staffing->jobTitle->job_title_id,
                    'job_title' => $staffing->jobTitle->name,
                    'start_date' => $staffing->start_date,
                    'end_date' => $staffing->end_date,
                ];
            })
            ->filter(function ($staffing, $index) {
                if ($index == 0) {
                    return true;
                }
                return $staffing['job_title'] != $this->staffings[$index - 1]->jobTitle->name;
            });

        // check if the employee current job title is already in the history
        // if so, then we don't need to add it again

        // check if the job title history is empty
        if ($jobTitleHistory->isNotEmpty()) {
            if ($jobTitleHistory->last()['job_title_id'] == $this->current_job_title->job_title_id) {
                return collect($jobTitleHistory);
            }
        }
        // append the current job title to the end of the collection
        $jobTitleHistory[] = [
            'job_title_id' => $this->current_job_title->job_title_id,
            'job_title' => $this->current_job_title->name,
            'start_date' => $this->current_job_title
                ->staffings()
                ->whereNull('end_date')
                ->first()
                ->start_date,
            'end_date' => null,
        ];

        return collect($jobTitleHistory);
    }

    /**
     * Mutator to fetch the department history of the employee
     * each consists of department name, start date, end date
     * and append the current department to the end of the collection
     */
    public function getDepartmentHistoryAttribute(): Collection
    {

        // get the department history of the employee
        // and check if the same department is repeated
        // one after the other, that means that the department
        // of the employee didn't change, so we don't need to
        // add it to the history
        $departmentHistory = $this->staffings()
            ->whereNotNull('end_date')
            ->orderByDesc('start_date')
            ->get()
            ->map(function ($staffing) {
                return [
                    'dep_id' => $staffing->department->dep_id,
                    'department' => $staffing->department->name,
                    'job_title' => [
                        'job_title_id' => $staffing->jobTitle->job_title_id,
                        'job_title' => $staffing->jobTitle->name,
                    ],
                    'start_date' => $staffing->start_date,
                    'end_date' => $staffing->end_date,
                ];
            })
            ->filter(function ($staffing, $index) {
                if ($index == 0) {
                    return true;
                }
                return $staffing['department'] != $this->staffings[$index - 1]->department->name;
            });

        // check if the employee current department is already in the history
        // if so, then we don't need to add it again


        // check if the department history is empty
        if (!$departmentHistory->isEmpty()) {
            if ($departmentHistory->last()['dep_id'] == $this->currentDepartment->dep_id) {
                return collect($departmentHistory);
            }
        }

        // append the current department to the end of the collection
        $departmentHistory[] = [
            'dep_id' => $this->currentDepartment->dep_id,
            'department' => $this->currentDepartment->name,
            'job_title' => [
                'job_title_id' => $this->currentJobTitle->job_title_id,
                'name' => $this->currentJobTitle->name,
            ],
            'start_date' => $this->currentDepartment
                ->staffings()
                ->whereNull('end_date')
                ->first()
                ->start_date,
            'end_date' => null,
        ];

        return collect($departmentHistory);
    }

    /**
     * Mutator to fetch the log of the employee
     * each consists of :
     * 1. action object
     * 2. list of affected users
     * 3. date
     */
    public function getLogAttribute(): Collection
    {
        $log = $this->user
            ->logs
            ->map(function ($log) {
                return [
                    'action' => new ActionResource($log->action),
                    'affected_users' => AffectedUserResource::collection($log->affectedUser),
//                    'description' => $log->description,
                    'date' => $log->date,
                ];
            });

        return collect($log);
    }
}
