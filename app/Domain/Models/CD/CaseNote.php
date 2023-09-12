<?php

namespace App\Domain\Models\CD;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class CaseNote extends Model
{
    use HasFactory;

    protected $primaryKey = 'id';
    protected $fillable = ['app_id', 'date', 'title', 'description'];

    public function appointment(): HasOne
    {
        return $this->hasOne(Appointment::class,'app_id','id');
    }
}
