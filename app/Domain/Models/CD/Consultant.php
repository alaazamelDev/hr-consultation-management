<?php

namespace App\Domain\Models\CD;

use App\Domain\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Notifications\Notifiable;

class Consultant extends Model
{
    use HasFactory;
    use Notifiable;

    protected $primaryKey = 'id';
    protected $fillable = ['user_id','clinic_id', 'first_name', 'last_name', 'birth_date', 'phone_number', 'address'];

    public function shifts(): HasMany
    {
        return $this->hasMany(Shift::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    public function clinic(): BelongsTo
    {
        return $this->belongsTo(Clinic::class);
    }

    public function getFullNameAttribute(): string
    {
        return "{$this->first_name} {$this->last_name}";
    }
}
