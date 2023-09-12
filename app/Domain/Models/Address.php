<?php

namespace App\Domain\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Address extends Model
{
    use HasFactory, SoftDeletes;

    protected $primaryKey = 'address_id';

    protected $fillable = [
        'state',
        'city',
        'street',
        'home_phone_no',
        'work_phone_no',
        'mobile_no',
        'email',
        'postal_code',
    ];

    public $timestamps = true;

    public function empData(): BelongsTo
    {
        return $this->belongsTo(EmpData::class, 'address_id', 'address_id');
    }
}
