<?php

namespace App\Domain\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Skill extends Model
{
    use HasFactory;
    protected $primaryKey = 'skill_id';

    protected $fillable = [
        'name',
    ];
    public function empsData(): BelongsToMany
    {
        return $this->belongsToMany(EmpData::class, 'emp_skills', 'skill_id', 'emp_data_id',
            'skill_id','emp_data_id');
    }
}
