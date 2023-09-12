<?php


namespace App\Infrastructure\Persistence\Eloquent;


use App\Domain\Models\Employee;
use App\Domain\Models\EmploymentStatus;
use App\Domain\Models\JobApplication;
use App\Domain\Models\JobTitle;
use App\Domain\Models\StaffPermission;
use App\Domain\Repositories\EmployeeRepositoryInterface;
use App\Domain\Repositories\UserRepositoryInterface;
use App\Exceptions\EntryNotFoundException;
use Exception;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Psr\Container\ContainerExceptionInterface;
use Throwable;

class EloquentEmployeeRepository implements EmployeeRepositoryInterface
{
    private UserRepositoryInterface $userRepository;

    public function __construct()
    {
        // initialize the user repository
        try {
            $this->userRepository = app()->make(UserRepositoryInterface::class);
        } catch (ContainerExceptionInterface $e) {
            report($e);
        }
    }

    /**
     */
    public function getEmployeeList(): LengthAwarePaginator
    {
        // implement search, filtration, and pagination
        $employees = Employee::query()
            ->with([
                'user',
                'schedule',
                'staffings',
                'jobApplication',
                'employmentStatuses',
                'staffings.jobTitle',
                'staffings.department',
                'jobApplication.empData',
            ]);

        // search by email
        if (request()->has('email')) {
            $employees->whereHas('user', function ($query) {
                $query->where('email', 'like', '%' . request()->get('email') . '%');
            });
        }

        // search by username
        if (request()->has('username')) {
            $employees->whereHas('user', function ($query) {
                $query->where('username', 'like', '%' . request()->get('username') . '%');
            });
        }

        // search by name (full name)
        if (request()->has('name')) {

            // get the name
            $name = request()->get('name');

            // trim & convert to lowercase
            $name = strtolower(trim($name));

            // search after ignoring the case
            $employees->whereHas('jobApplication', function ($query) use ($name) {
                $query->whereHas('empData', function ($query) use ($name) {
                    $query->whereRaw('LOWER(first_name) LIKE ?', ["%$name%"])
                        ->orWhereRaw('LOWER(last_name) LIKE ?', ["%$name%"])
                        ->orWhereRaw('CONCAT(LOWER(first_name), " ", LOWER(last_name)) LIKE ?', ["%$name%"]);
                });
            });
        }

        // filter by scheduleId
        if (request()->has('schedule')) {

            // get the schedules
            $schedules = request()->get('schedule');

            // extract the comma separated values
            $schedules = explode(',', $schedules);

            // convert it to array of integers
            $schedules = array_map('intval', $schedules);

            // filter the result based on schedule IDs
            $employees->whereIn('schedule_id', $schedules);
        }

        // filter by departmentId
        if (request()->has('dep')) {

            // get the departments
            $departments = request()->get('dep');

            // extract the comma separated values
            $departments = explode(',', $departments);

            // convert it to array of integers
            $departments = array_map('intval', $departments);

            // filter the result based on department IDs
            $employees->whereHas('staffings', function ($query) use ($departments) {

                // get the latest staffing record (where end date = null & start date is the latest)
                $query->whereNull('end_date')
                    ->latest('start_date')
                    ->whereIn('dep_id', $departments);
            });
        }

        // filter by titleId
        if (request()->has('title')) {

            // get the titles
            $titles = request()->get('title');

            // extract the comma separated values
            $titles = explode(',', $titles);

            // convert it to array of integers
            $titles = array_map('intval', $titles);

            // filter the result based on title IDs
            $employees->whereHas('staffings', function ($query) use ($titles) {

                // get the latest staffing record (where end date = null & start date is the latest)
                $query->whereNull('end_date')
                    ->latest('start_date')
                    ->whereIn('job_title_id', $titles);

            });
        }

        // filter by employment status
        if (request()->has('status')) {

            // get the statuses
            $statuses = request()->get('status');

            // extract the comma separated values
            $statuses = explode(',', $statuses);

            // convert it to array of integers
            $statuses = array_map('intval', $statuses);

            // filter the result based on status IDs
            // by checking the M2M relationship between employee and employment status
            // and get the latest record in the pivot table (where end date = null & start date is the latest)
            $employees->whereHas('employmentStatuses', function ($query) use ($statuses) {
                $query->whereNull('end_date')
                    ->latest('start_date')
                    ->whereIn('employment_statuses.emp_status_id', $statuses);
            });
        }

        return $employees->paginate(10);

    }

    public function getAllEmployees(): LengthAwarePaginator
    {
        $employees = Employee::query();

        // implement search by name (first, or last or full name)
        if (request()->has('name')) {

            // get the name
            $name = request()->get('name');

            // trim & convert to lowercase
            $name = strtolower(trim($name));

            // search after ignoring the case
            $employees
                ->whereHas('jobApplication', function ($query) use ($name) {
                    $query->whereHas('empData', function ($query) use ($name) {
                        $query->whereRaw('LOWER(first_name) LIKE ?', ["%$name%"])
                            ->orWhereRaw('LOWER(last_name) LIKE ?', ["%$name%"])
                            ->orWhereRaw('CONCAT(LOWER(first_name), " ", LOWER(last_name)) LIKE ?', ["%$name%"]);
                    });
                })
                ->with('user');
        }

        return $employees->paginate(100);
    }

    /**
     * @throws EntryNotFoundException
     */
    public function getJobTitlesHistory(int $id)
    {
        try {
            // get the employee
            $employee = $this->getEmployeeById($id);

        } catch (Exception) {
            throw new EntryNotFoundException("Employee with ID $id not found");
        }

        // get the job titles history
        return $employee->job_title_history;
    }

    /**
     * @throws EntryNotFoundException
     */
    public function getDepartmentsHistory(int $id)
    {
        try {
            // get the employee
            $employee = $this->getEmployeeById($id);

        } catch (Exception) {
            throw new EntryNotFoundException("Employee with ID $id not found");
        }

        // get the departments history
        return $employee->department_history;
    }

    public function getEmployeeListByDepId(int $dep_id): array
    {
        return Employee::query()->where('cur_dep', '=', $dep_id)->get()->toArray();
    }

    public function getEmployeeListByTitleId(int $title_id): array
    {
        return Employee::query()->where('cur_title', '=', $title_id)->get()->toArray();
    }

    /**
     * @throws EntryNotFoundException
     */
    public function getEmployeeById(int $id): Builder|Model
    {
        try {
            $employee = Employee::query()
                ->where('emp_id', '=', $id)
                ->firstOrFail();
        } catch (Exception) {
            throw new EntryNotFoundException("Employee with ID $id not found");
        }

        return $employee;
    }

    /**
     * @throws Exception
     * @throws Throwable
     */
    public function createEmployee(array $data): Builder|Model
    {
        try {
            // start transaction
            DB::beginTransaction();

            // first, user should be created
            $user = $this->userRepository->createUser([
                'user_type_id' => 1,
                'email' => $data['email'],
                'username' => $data['username'],
                'password' => $data['password'],
            ]);

            // get the dep_id for the employee from the job vacancy
            // that is associated with the job application
            $dep_id = JobApplication::query()
                ->where('job_app_id', '=', $data['job_app_id'])
                ->firstOrFail()
                ->jobVacancy
                ->dep_id;

            // then, employee should be created
            $employee = Employee::query()->create([
                'user_id' => $user->user_id,
                'job_app_id' => $data['job_app_id'],
                'schedule_id' => $data['schedule_id'],

                // meta data for employee
                'cur_title' => $data['job_title_id'],
                'cur_dep' => $dep_id,
            ]);

            // create a staffing record for the employee
            $employee->staffings()->create([
                'job_title_id' => $data['job_title_id'],
                'dep_id' => $dep_id,
                'start_date' => $data['start_date'],
            ]);

            // attach an employment status record for the employee (working by default)
            $employee->employmentStatuses()->attach(EmploymentStatus::WORKING, [
                'start_date' => $data['start_date'],
            ]);

            // get the list of permissions associated with the job title
            $jobTitlePermissions = JobTitle::query()
                ->where('job_title_id', '=', $data['job_title_id'])
                ->firstOrFail()
                ->permissions;

            // additional permissions
            $additional_permissions = array();

            // go through each additional permission, and make sure
            // each permission (check it's perm_id) is not found
            // in the list of job title permissions
            if (isset($data['additional_permissions'])) {
                foreach ($data['additional_permissions'] as $permission) {
                    if (!in_array($permission, $jobTitlePermissions->pluck('perm_id')->toArray())) {

                        // if the permission is not found, then add it to the list
                        $additional_permissions[] = $permission;

                    }
                }

                // attach the additional permissions to the employee (with status = 1)
                // and attach the excluded permissions to the employee (with status = 0)
                // in the staffing record

                if (count($additional_permissions) > 0) {
                    $employee->staffings()->whereNull('end_date')->latest()->firstOrFail()->permissions()
                        ->attach($additional_permissions, [
                            'status' => 1,
                        ]);
                    $employee->staffings()->whereNull('end_date')->latest()->firstOrFail()->permissions()
                        ->attach($additional_permissions, [
                            'status' => 1,
                        ]);
                }

            }

            $excluded_permissions = optional($data)['excluded_permissions'];

            if (isset(optional($data)['excluded_permissions'])) {

                $employee->staffings()->whereNull('end_date')->latest()->firstOrFail()->permissions()
                    ->attach($excluded_permissions, [
                        'status' => 0,
                    ]);
            }

            // update job application status to hired
            $jobApplication = JobApplication::query()
                ->where('job_app_id', '=', $data['job_app_id'])
                ->firstOrFail();

            $jobApplication->update([
                'app_status_id' => 5,
            ]);

            // commit transaction
            DB::commit();

            return $employee;

        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * @throws EntryNotFoundException
     */
    public function getEmployeeAbsenceHistory(int $id): array
    {
        try {

            // get the employee absences from eloquent absence repository
            $employee = Employee::with(['absences', 'absences.absenceStatus'])
                ->where('emp_id', '=', $id)
                ->firstOrFail();
        } catch (Exception) {
            throw new EntryNotFoundException("Employee with ID $id not found");
        }

        $absences = $employee
            ->absences
            ->sortByDesc('absence_date')
            ->values();

        return $absences->map(function ($absence) {
            return [
                'emp_name' => $absence->employee->full_name,
                'absence_id' => $absence->absence_id,
                'absence_date' => $absence->absence_date,
                'absence_status' => [
                    'absence_status_id' => $absence->absenceStatus->absence_status_id,
                    'absence_status_name' => $absence->absenceStatus->absence_status_id == 1 ? 'مبرر' : 'غير مبرر',
                ],
            ];
        })->toArray();
    }

    /**
     * @throws EntryNotFoundException
     */
    public function deleteEmployee($id): Builder|Model|null
    {
        try {
            $employee = Employee::query()
                ->where('emp_id', '=', $id)
                ->firstOrFail();
        } catch (Exception) {
            throw new EntryNotFoundException("employee with id $id not found");
        }

        // delete the employee
        $employee->delete();

        return $employee;
    }

    /**
     * @throws EntryNotFoundException
     */
    public function editEmployeeCredentials(int $id, array $data): Employee|Builder|null
    {

        try {

            $employee = Employee::query()
                ->with(['user'])
                ->where('emp_id', '=', $id)
                ->firstOrFail();

        } catch (Exception) {
            throw new EntryNotFoundException("employee with id $id not found");
        }

        $employee->user->update([
            'email' => optional($data)['email'] ?? $employee->user->email,
            'username' => optional($data)['username'] ?? $employee->user->username,
            'password' => optional($data)['password']
                ? Hash::make($data['password'])
                : $employee->user->password,
        ]);
        return $employee;
    }

    /**
     * @throws EntryNotFoundException
     * @throws Exception
     * @throws Throwable
     */
    public function editEmployeeDepartment(int $id, array $data): Employee|Builder|null
    {
        try {

            $employee = Employee::query()
                ->with(['staffings' => function ($query) {
                    $query->with('jobTitle')->whereNull('end_date')->latest();
                }])->findOrFail($id);


        } catch (Exception) {
            throw new EntryNotFoundException("employee with id $id not found");
        }

        // if the new dep_id is the same as the current one, do nothing
        try {

            DB::beginTransaction();
            if ($employee->current_department->dep_id == $data['dep_id']) {
                return $employee;
            }

            // if the new dep_id is different from the current one,
            // add an end_date to the current staffing record,
            // and create a new staffing record with the new dep_id and the existing job_title_id
            $previousStaffingRecord = $employee->staffings()
                ->whereNull('end_date')
                ->first();

            $employee->staffings()->create([
                'job_title_id' => $employee->current_job_title->job_title_id,
                'dep_id' => $data['dep_id'],
                'start_date' => Carbon::now(),
            ]);

            $previousStaffingRecord->update([
                'end_date' => Carbon::now(),
            ]);

            // update cur_dep in employee table
            $employee->update([
                'cur_dep' => $data['dep_id'],
            ]);

            $new_staffing_id = $employee->staffings()
                ->whereNull('end_date')
                ->latest()
                ->first()
                ->staff_id;

            // update staff permissions related to the previous staffing record
            // to have the new staffing record id
            StaffPermission::query()
                ->where('staff_id', '=', $previousStaffingRecord->staff_id)
                ->update([
                    'staff_id' => $new_staffing_id,
                ]);

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }

        return $employee;
    }

    /**
     * @throws EntryNotFoundException
     */
    public function editEmployeeSchedule(int $id, array $data): Employee|Builder|null
    {
        try {

            $employee = Employee::query()
                ->findOrFail($id);

        } catch (Exception) {
            throw new EntryNotFoundException("employee with id $id not found");
        }

        // if the new schedule_id is the same as the current one, do nothing
        if ($employee->schedule_id == $data['schedule_id']) {
            return $employee;
        }

        // if the new schedule_id is different from the current one, update it
        $employee->update([
            'schedule_id' => $data['schedule_id'],
        ]);


        return $employee;
    }


    /**
     * @throws EntryNotFoundException
     * @throws Exception
     * @throws Throwable
     */
    public function editEmployeeEmploymentStatus(int $id, array $data): Employee|Builder|null
    {
        // add an end_date to the current employment status record
        // and create a new employment status record with the new status
        // and and start_date of today
        // only if the new status is different from the current one
        try {
            $employee = Employee::query()
                ->findOrFail($id);
        } catch (Exception) {
            throw new EntryNotFoundException("employee with id $id not found");
        }

//        dd($employee->current_employment_status->emp_status_id, $data['emp_status_id']);

        // if the new status is the same as the current one, do nothing
        if ($employee->current_employment_status->emp_status_id == $data['emp_status_id']) {
            return $employee;
        }

        try {
            DB::beginTransaction();

            // if the new status is different from the current one,
            // add an end_date to the current employment status record,
            // and create a new employment status record with the new status
            // and and start_date of today
            $previousEmploymentStatusRecord = $employee->current_employment_status;
            $employee->employmentStatuses()->attach($data['emp_status_id'], [
                'start_date' => Carbon::now(),
            ]);
            $previousEmploymentStatusRecord->pivot->update([
                'end_date' => Carbon::now(),
            ]);

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
        return $employee;
    }

    // Never delete this
//    public function editEmployeePermissions(int $id , array $data): Employee|Builder|null
//    {
//        $employee = Employee::query()
//            ->with(['staffings' => function ($query) {
//                $query->with('jobTitle','permissions')->whereNull('end_date')->latest();
//            }])
//            ->find($id);
//        if(!$employee){
//            return $employee;
//        }
//        if(!$employee->staffings->first()){
//            $employee['message'] = "employee no longer works in any department";
//            $employee['status'] = 400;
//            return $employee;
//        }
//
//        // Update the job title (if its updated)
//        if( $employee->cur_title != $data['job_title_id'] ){
//           $employee->staffings()->whereNull('end_date')->first()->update(['job_title_id'=>$data['job_title_id']]);
//           $employee->cur_title = $data['job_title_id'];
//           $employee->save();
//        }
//
//        // Update the permissions including the additional and the new job title permissions (if they are updated)
//        if(array_key_exists('permissions_ids', $data)) {
//            $employee->staffings()->whereNull('end_date')->first()->permissions()->detach();
//            foreach ($data['permissions_ids'] as $permissions_id) {
//                $employee->staffings()->whereNull('end_date')->first()->permissions()->attach($permissions_id, [
//                    'status' => 1,
//                    'created_at' => now(),
//                    'updated_at' => now()
//                ]);
//                $employee->save();
//            }
//        }
//        // Reload the staffings relationship after the update
//        $employee->load([
//            'staffings' => function ($query) {
//                $query->whereNull('end_date');
//            },
//            'staffings.jobTitle',
//            'staffings.permissions'
//        ]);
//
//        return $employee;
//    }

    public function editEmployeePermissions(int $id, array $data): Employee|Builder|null
    {
        $employee = Employee::query()
            ->with(['staffings' => function ($query) {
                $query->with('jobTitle', 'permissions')->whereNull('end_date')->latest();
            }])
            ->find($id);
        if (!$employee) {
            return $employee;
        }
        if (!$employee->staffings->first()) {
            $employee['message'] = "employee no longer works in any department";
            $employee['status'] = 400;
            return $employee;
        }


        $current_job_title_permissions_ids = null;
        $new_job_title_permissions_ids = null;
        $new_additional_permissions_ids = null;

        $current_job_title_permissions = $employee->staffings->first()->jobTitle->permissions;
        foreach ($current_job_title_permissions as $current_job_title_permission)
            $current_job_title_permissions_ids[] = $current_job_title_permission->perm_id;


        // Update the job title (if its updated)
        if (array_key_exists('job_title_id', $data)) {
            if ($employee->cur_title != $data['job_title_id']) {

                if (!empty($current_job_title_permissions_ids)) {
                    $employee->staffings->first()->permissions()
                        ->wherePivotIn('perm_id', $current_job_title_permissions_ids)
                        ->wherePivot('status', 0)
                        ->detach();
                }
                $employee->save();

                $employee->staffings->first()->update(['job_title_id' => $data['job_title_id']]);
                $employee->cur_title = $data['job_title_id'];
                $employee->save();

                // For updated permissions
                $eloquentJobTitleRepository = new EloquentJobTitleRepository();
                $new_job_title_permissions = $eloquentJobTitleRepository->getJobTitleById($data['job_title_id'])->permissions;
                foreach ($new_job_title_permissions as $new_job_title_permission)
                    $new_job_title_permissions_ids[] = $new_job_title_permission->perm_id;
            }
        }

        // Update the additional permissions (if they are updated)
        if (array_key_exists('additional_permissions_ids', $data)) {

            if (empty($data['additional_permissions_ids'])) {
                $employee->staffings->first()->permissions()
                    ->wherePivot('status', 1)
                    ->detach();
            } else {
                foreach ($data['additional_permissions_ids'] as $additional_permissions_id) {
                    if ($new_job_title_permissions_ids == null) {
                        if (in_array($additional_permissions_id, $current_job_title_permissions_ids)) {
                            $employee->staffings->first()->permissions()
                                ->wherePivot('perm_id', $additional_permissions_id)
                                ->detach();
                        } else {
                            $new_additional_permissions_ids[] = $additional_permissions_id;
                        }
                    } else if (!in_array($additional_permissions_id, $new_job_title_permissions_ids)) { //TODO if id is in deleted array !
                        $new_additional_permissions_ids[] = $additional_permissions_id;
                    }
                }
            }
        }

        if (array_key_exists('deleted_permissions_ids', $data)) {
            foreach ($data['deleted_permissions_ids'] as $deleted_permissions_id) {
                if (($new_job_title_permissions_ids !== null && in_array($deleted_permissions_id, $new_job_title_permissions_ids)) ||
                    ($current_job_title_permissions_ids !== null && in_array($deleted_permissions_id, $current_job_title_permissions_ids)) &&
                    !($employee->staffings->first()->permissions()->wherePivot('perm_id', $deleted_permissions_id)->wherePivot('status', 0)->exists())) {
                    $employee->staffings->first()->permissions()->attach($deleted_permissions_id, [
                        'status' => 0,
                        'created_at' => now(),
                        'updated_at' => now()
                    ]);
                }
            }
        }

        if ($new_additional_permissions_ids != null) {
            $employee->staffings->first()->permissions()
                ->wherePivot('status', 1)
                ->detach();
            foreach ($new_additional_permissions_ids as $new_additional_permissions_id) {
                $employee->staffings->first()->permissions()->attach($new_additional_permissions_id, [
                    'status' => 1,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
                $employee->save();
            }
        }

        // Reload the staffings relationship after the update
        $employee->load([
            'staffings' => function ($query) {
                $query->whereNull('end_date');
            },
            'staffings.jobTitle.permissions',
            'staffings.permissions'
        ]);

        return $employee;
    }
}
