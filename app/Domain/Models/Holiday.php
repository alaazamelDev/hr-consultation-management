<?php

namespace App\Domain\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Holiday extends Model
{
    use HasFactory,SoftDeletes;

    protected $primaryKey = 'holiday_id';
    protected $fillable = ['name', 'date', 'is_recurring'];

}
