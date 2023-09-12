<?php

namespace App\Domain\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EducationRecord extends Model
{
    use HasFactory, SoftDeletes;

    protected $primaryKey = 'education_record_id';
    protected $fillable = [
        'emp_data_id',
        'education_level_id',
        'univ_name',
        'city',
        'start_date',
        'end_date',
        'specialize',
        'grade',
    ];
}
