<?php

namespace App\Domain\Models\CD;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AppointmentStatus extends Model
{
    use HasFactory;

    const STATUS_CANCELED_BY_CUSTOMER = 1;
    const STATUS_CANCELED_BY_EMPLOYEE = 2;
    const STATUS_CANCELED_BY_CONSULTANT = 3;
    const STATUS_COMPLETED = 4;
    const STATUS_RESERVED = 5;
    const STATUS_AVAILABLE = 6;
    const STATUS_MISSED_BY_CUSTOMER = 7;
    const STATUS_MISSED_BY_CONSULTANT = 8;
    const STATUS_RESERVED_ON_PHONE = 9;
    const STATUS_CLOSED = 10;
    const  STATUS_ATTENDANCE_IS_NOT_RECORDED = 11;

    protected $primaryKey = 'id';
    protected $fillable = ['name'];

    public function appointments(): HasMany
    {
        return $this->hasMany(Appointment::class);
    }
}
