<?php

namespace App\Domain\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Domain\Models\CD\Consultant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use JetBrains\PhpStorm\Pure;
use Laravel\Sanctum\HasApiTokens;

/**
 * @property mixed email
 * @property mixed username
 * @property mixed usertype
 */
class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $primaryKey = 'user_id';
    protected $fillable = [
        'user_type_id',
        'email',
        'username',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function employee(): HasOne
    {
        return $this->hasOne(Employee::class, 'user_id', 'user_id');
    }

    public function consultant(): HasOne
    {
        return $this->hasOne(Consultant::class, 'user_id', 'user_id');
    }

    public function usertype(): BelongsTo
    {
        return $this->belongsTo(UserType::class, 'user_type_id', 'user_type_id');
    }

    public function actions(): BelongsToMany
    {
        return $this->belongsToMany(Action::class, 'logs', 'user_id', 'action_id',
            'user_id', 'action_id')
            ->withPivot('description', 'date');
    }

    /**
     * User and Action is M2M
     * User and Log is M2M and Affected User is pivot table
     *
     *Here is a reference for the pivot table
     */
    public function logs(): BelongsToMany
    {
        return $this->belongsToMany(Log::class, 'affected_users', 'user_id', 'log_id',
            'user_id', 'log_id')
            ->withPivot('affected_user_id');
    }

    public function affectedUser(): HasMany
    {
        return $this->hasMany(AffectedUser::class, 'user_id', 'user_id');
    }

    public function isEmployee(): bool
    {
        return $this->usertype->user_type_id === 1;
    }

    #[Pure] public function getFullNameAttribute()
    {
        if ($this->isEmployee()) {
            return $this->employee->full_name;
        }
        return null;
    }

}
