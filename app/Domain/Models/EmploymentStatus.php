<?php

namespace App\Domain\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * @property mixed emp_status_id
 * @property mixed name
 * @property mixed description
 */
class EmploymentStatus extends Model
{
    use HasFactory;

    protected $primaryKey = 'emp_status_id';
    protected $fillable = ['name', 'description'];

    public const WORKING = 1;
    public const VACATION = 2;
    public const RESIGNED = 3;
    public const TEMPORARY_SUSPENSION = 4;

    public function employees(): BelongsToMany
    {
        return $this->belongsToMany(Employee::class, 'employee_statuses', 'emp_status_id', 'emp_id',
            'emp_status_id', 'emp_id')
            ->withPivot('start_date', 'end_date');
    }


}
