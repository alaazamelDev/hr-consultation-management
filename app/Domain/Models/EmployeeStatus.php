<?php

namespace App\Domain\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeStatus extends Model
{
    use HasFactory;
    protected $primaryKey = 'status_id';
    protected $fillable = ['emp_id', 'emp_status_id', 'start_date', 'end_date'];
}
