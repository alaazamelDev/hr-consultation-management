<?php

namespace App\Domain\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Action extends Model
{
    use HasFactory;

    protected $primaryKey = 'action_id';
    protected $fillable = ['name', 'description', 'severity'];

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'logs', 'action_id', 'user_id',
            'action_id', 'user_id')
            ->withPivot('description', 'date');
    }

    public function log(): HasMany
    {
        return $this->hasMany(Log::class, 'action_id', 'action_id');
    }

}
