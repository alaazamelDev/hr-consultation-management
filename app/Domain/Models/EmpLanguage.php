<?php

namespace App\Domain\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmpLanguage extends Model
{
    use HasFactory;
    protected $primaryKey = 'emp_lang_id';
    protected $fillable = [
        'emp_data_id',
        'language_id',
        'speaking_level',
        'writing_level',
        'reading_level',
    ];
}
