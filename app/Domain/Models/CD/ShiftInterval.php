<?php

namespace App\Domain\Models\CD;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ShiftInterval extends Model
{
    use HasFactory;

    protected $primaryKey = 'id';
    protected $fillable = ['shift_id', 'interval_id'];

    public function shift(): BelongsTo
    {
        return $this->belongsTo(Shift::class);
    }

    public function interval(): BelongsTo
    {
        return $this->belongsTo(Interval::class);
    }
}
