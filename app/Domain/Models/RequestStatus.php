<?php

namespace App\Domain\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RequestStatus extends Model
{
    use HasFactory;
    protected $primaryKey = 'req_stat_id';
    protected $fillable = ['name', 'description'];

    public function shiftRequests()
    {
        return $this->hasMany(ShiftRequest::class, 'req_stat_id', 'req_stat_id');
    }
}
