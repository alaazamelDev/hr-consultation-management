<?php

namespace App\Domain\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Department
 * @package App\Domain\Models
 *
 * @property int dep_id
 * @property string name
 * @property string description
 */
class Department extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $primaryKey = 'dep_id';
    protected $fillable = ['name', 'description'];

    public function staffings(): HasMany
    {
        return $this->hasMany(Staffing::class, 'dep_id', 'dep_id');
    }

    public function jobVacancies(): HasMany
    {
        return $this->hasMany(JobVacancy::class, 'dep_id', 'dep_id');
    }
}
