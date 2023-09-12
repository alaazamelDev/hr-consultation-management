<?php

namespace App\Domain\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrainingCourse extends Model
{
    use HasFactory;
    protected $primaryKey = 'training_course_id';

    protected $fillable = [
        'emp_data_id',
        'name',
        'institute_name',
        'city',
        'start_date',
        'end_date',
        'specialize',
    ];

    public function empData()
    {
        return $this->belongsTo(EmpData::class, 'emp_data_id', 'emp_data_id');
    }
}
