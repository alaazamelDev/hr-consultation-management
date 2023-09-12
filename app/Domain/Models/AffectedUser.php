<?php

namespace App\Domain\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AffectedUser extends Model
{
    use HasFactory;

    protected $primaryKey = 'affected_user_id';
    protected $fillable = [
        'user_id',
        'log_id',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    public function log(): BelongsTo
    {
        return $this->belongsTo(Log::class, 'log_id', 'log_id');
    }

    /**
     * mutator that returns the full name of the user
     */
    public function getFullNameAttribute(): string
    {
        $user = $this->user;
        $employee = $user->employee;
        if ($employee) {
            return $employee->full_name;
        }
        return '';
    }
}
