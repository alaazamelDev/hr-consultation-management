<?php

namespace App\Domain\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Relative extends Model
{
    use HasFactory;

    protected $primaryKey = 'relative_id';

    protected $fillable = [
        'emp_data_id',
        'relative_data_id',
    ];

    public function empData(): HasOne
    {
        return $this->hasOne(EmpData::class, 'emp_data_id', 'emp_data_id');
    }

    public function relativeData(): HasOne
    {
        return $this->hasOne(EmpData::class, 'emp_data_id', 'relative_data_id');
    }
}
