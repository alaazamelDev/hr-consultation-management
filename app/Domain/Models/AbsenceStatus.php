<?php

namespace App\Domain\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AbsenceStatus extends Model
{
    use HasFactory;
    protected $primaryKey = 'absence_status_id';
    protected $fillable = ['name', 'description'];

    public function absences(): HasMany
    {
        return $this->hasMany(Absence::class, 'absence_status_id', 'absence_status_id');
    }

}
