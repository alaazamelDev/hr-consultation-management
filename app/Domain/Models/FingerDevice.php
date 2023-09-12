<?php

namespace App\Domain\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FingerDevice extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        "name",
        "ip",
        "serialNumber",
    ];
}

