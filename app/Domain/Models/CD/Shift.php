<?php

namespace App\Domain\Models\CD;

use App\Domain\Models\EmploymentStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Shift extends Model
{
    use HasFactory;

    protected $primaryKey = 'id';
    protected $fillable = ['consultant_id', 'name', 'slot_duration'];

    public function consultant(): BelongsTo
    {
        return $this->belongsTo(Consultant::class);
    }

    public function workDays(): HasMany
    {
        return $this->hasMany(WorkDay::class);
    }

    public function intervals(): BelongsToMany
    {
        return $this->belongsToMany(Interval::class , 'shift_intervals');
    }
}
