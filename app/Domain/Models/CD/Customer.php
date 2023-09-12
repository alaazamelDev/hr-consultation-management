<?php

namespace App\Domain\Models\CD;

use App\Domain\Models\EducationLevel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Customer extends Model
{
    use HasFactory;
    use HasApiTokens;
    use Notifiable;


    protected $primaryKey = 'id';
    protected $fillable = [
        'education_level_id',
        'first_name',
        'last_name',
        'job',
        'birth_date',
        'phone',
        'phone_number',
        'martial_status',
        'num_of_children',
        'national_number',
        'profile_picture',
        'username',
        'password',
        'verified',
        'blocked',
        'email',
        'email_verified_at',
        ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'verified' => 'boolean',
        'blocked' => 'boolean'
    ];

    public function educationLevel(): BelongsTo
    {
        return $this->belongsTo(EducationLevel::class, 'education_level_id', 'education_level_id');
    }

    public function appointments(): HasMany
    {
        return $this->hasMany(Appointment::class);
    }

    public function getFullNameAttribute(): string
    {
        return "{$this->first_name} {$this->last_name}";
    }
}
