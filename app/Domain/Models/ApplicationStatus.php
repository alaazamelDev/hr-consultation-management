<?php

namespace App\Domain\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property mixed app_status_id
 * @property mixed name
 * @property mixed description
 */
class ApplicationStatus extends Model
{
    use HasFactory;

    protected $primaryKey = 'app_status_id';
    protected $fillable = ['name', 'description'];

    public function jobApplications(): HasMany
    {
        return $this->hasMany(JobApplication::class, 'app_status_id', 'app_status_id');
    }
}
