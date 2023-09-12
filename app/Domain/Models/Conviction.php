<?php

namespace App\Domain\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Conviction extends Model
{
    use HasFactory, SoftDeletes;

    protected $primaryKey = 'conviction_id';

    protected $fillable = [
        'emp_data_id',
        'description',
    ];

    public function empData(): BelongsTo
    {
        return $this->belongsTo(EmpData::class, 'emp_data_id', 'emp_data_id');
    }
}
