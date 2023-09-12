<?php

namespace App\Domain\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class Log extends Model
{
    use HasFactory;

    protected $primaryKey = 'log_id';
    protected $fillable = ['user_id', 'action_id', 'description', 'date'];

    public function action(): BelongsTo
    {
        return $this->belongsTo(Action::class, 'action_id', 'action_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    public function affectedUser(): HasMany
    {
        return $this->hasMany(AffectedUser::class, 'log_id', 'log_id');
    }

    public function getAffectedUserIdsAttribute(): AnonymousResourceCollection
    {
        return $this->affectedUser->pluck('user_id')->distinct();
    }


}
