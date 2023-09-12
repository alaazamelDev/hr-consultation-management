<?php

namespace App\Domain\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmpComputerSkill extends Model
{
    use HasFactory;
    protected $primaryKey = 'emp_com_skill_id';
    protected $fillable = [
        'emp_data_id',
        'computer_skill_id',
        'level',
    ];

}
