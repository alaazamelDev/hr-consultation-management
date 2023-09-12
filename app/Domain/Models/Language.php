<?php

namespace App\Domain\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Language extends Model
{
    use HasFactory;
    protected $primaryKey = 'language_id';
    protected $fillable = ['name'];

    public function empsData()
    {
        return $this->belongsToMany(EmpData::class, 'emp_languages', 'language_id', 'emp_data_id',
            'language_id','emp_data_id')
            ->withPivot('speaking_level', 'writing_level', 'reading_level');
    }
}
