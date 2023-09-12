<?php

namespace App\Domain\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class ShiftRequest extends Model
{
    use HasFactory, SoftDeletes;

    protected $primaryKey = 'shift_req_id';
    protected $fillable = [
        'emp_id',
        'req_stat',
        'description',
        'new_time_in',
        'new_time_out',
        'start_date',
        'duration',
        'remaining_days',
    ];

    protected $casts = [
        'start_date' => 'date:Y-m-d',
        'duration' => 'integer',
        'remaining_days' => 'integer',
    ];

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'emp_id', 'emp_id');
    }

}
