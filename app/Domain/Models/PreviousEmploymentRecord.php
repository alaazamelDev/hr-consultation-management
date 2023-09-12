<?php

namespace App\Domain\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class PreviousEmploymentRecord extends Model
{
    use HasFactory, SoftDeletes;

    protected $primaryKey = 'prev_emp_record_id';

    protected $fillable = [
        'emp_data_id',
        'employer_name',
        'start_date',
        'end_date',
        'address',
        'telephone',
        'job_title',
        'job_description',
        'salary',
        'allowance',
        'quit_reason',
    ];

    public function empData(): BelongsTo
    {
        return $this->belongsTo(EmpData::class, 'emp_data_id', 'emp_data_id');
    }
}
