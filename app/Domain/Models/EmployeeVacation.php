<?php

namespace App\Domain\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class EmployeeVacation extends Model
{
    use HasFactory, SoftDeletes;

    protected $primaryKey = 'employee_vacation_id';
    protected $fillable = [
        'emp_id',
        'start_date',
        'total_days',
        'remaining_days'
    ];

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'emp_id', 'emp_id');
    }
}
