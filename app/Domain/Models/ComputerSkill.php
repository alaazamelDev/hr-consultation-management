<?php

namespace App\Domain\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class ComputerSkill extends Model
{
    use HasFactory;
    protected $primaryKey = 'computer_skill_id';

    protected $fillable = ['name'];

    public function empsData(): BelongsToMany
    {
        return $this->belongsToMany(EmpData::class, 'emp_computer_skills', 'computer_skill_id', 'emp_data_id',
            'computer_skill_id','emp_data_id')
            ->withPivot('level');
    }
}
