<?php

namespace App\Domain\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmpSkill extends Model
{
    use HasFactory;
    protected $primaryKey = 'emp_skill_id';

    protected $fillable = [
        'skill_id',
        'emp_data_id',
    ];
}
