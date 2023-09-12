<?php

namespace App\Domain\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class JobTitle extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $primaryKey = 'job_title_id';
    protected $fillable = ['name', 'description'];

    public function staffings(): HasMany
    {
        return $this->hasMany(
            Staffing::class,
            'job_title_id',
            'job_title_id'
        );
    }

    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(
            Permission::class,
            'job_title_permissions',
            'job_title_id',
            'perm_id',
            'job_title_id',
            'perm_id'
        );
    }

    /**
     * employees count mutator.
     * this function is used to get the number of employees that have this job title
     * as their active job title
     * @return int
     */
    public function getEmployeesCountAttribute(): int
    {
        return $this->staffings()->whereNull('end_date')->count();
    }
}
