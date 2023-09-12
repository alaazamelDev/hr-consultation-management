<?php

namespace App\Domain\Models\CD;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;
    protected $fillable = [
        'title', 'address','side_address', 'description', 'link', 'image', 'blurhash', 'start_date', 'end_date'
    ];

    protected $dates = [
        'start_date', 'end_date'
    ];

}
