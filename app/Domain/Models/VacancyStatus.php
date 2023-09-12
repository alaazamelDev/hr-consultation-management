<?php

namespace App\Domain\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class VacancyStatus extends Model
{
    use HasFactory;
    protected $primaryKey = 'vacancy_status_id';
    protected $fillable = ['name', 'description'];

    public function jobVacancies(): HasMany
    {
        return $this->hasMany(JobVacancy::class, 'vacancy_status_id', 'vacancy_status_id');
    }
}
