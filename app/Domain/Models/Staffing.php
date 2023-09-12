<?php

namespace App\Domain\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property Department current_department
 * @property JobTitle current_job_title
 * @property mixed jobTitle
 * @property mixed $permissions
 */
class Staffing extends Model
{
    use HasFactory, SoftDeletes;

    protected $primaryKey = 'staff_id';
    protected $fillable = ['emp_id', 'job_title_id', 'dep_id', 'start_date', 'end_date'];

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'emp_id', 'emp_id');
    }

    public function jobTitle(): BelongsTo
    {
        return $this->belongsTo(JobTitle::class, 'job_title_id', 'job_title_id');
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class, 'dep_id', 'dep_id');
    }

    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(Permission::class, 'staff_permissions', 'staff_id', 'perm_id',
            'staff_id', 'perm_id')
            ->withPivot('status');
    }

    /**
     * Get Current Department Mutator.
     * this function is used to get the current department of the employee
     * by checking the end_date of the staffing record
     */
    public function getCurrentDepartmentAttribute(): Model|BelongsTo|null
    {
        return $this->department()->whereNull('end_date')->first();
    }

    /**
     * Get Current Job Title Mutator.
     * this function is used to get the current job title of the employee
     * by checking the end_date of the staffing record
     */
    public function getCurrentJobTitleAttribute(): Model|BelongsTo|null
    {
        return $this->jobTitle()->whereNull('end_date')->first();
    }
}
