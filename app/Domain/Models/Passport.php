<?php

namespace App\Domain\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Passport extends Model
{
    use HasFactory, SoftDeletes;

    protected $primaryKey = 'passport_id';

    protected $fillable = [
        'passport_number',
        'place_of_issue',
        'date_of_issue',
    ];

    public function empData()
    {
        return $this->hasOne(EmpData::class, 'passport_id', 'passport_id');
    }
}
