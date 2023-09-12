<?php

namespace App\Domain\Models\CD;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UnRegisteredAccount extends Model
{
    use HasFactory;

    protected $fillable = ['app_id', 'name', 'phone_number'];

    // Define relationships, if any
    public function appointment(): BelongsTo
    {
        return $this->belongsTo(Appointment::class, 'app_id', 'id');
    }
}
