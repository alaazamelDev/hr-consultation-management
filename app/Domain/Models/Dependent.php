<?php

namespace App\Domain\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Dependent extends Model
{
    use HasFactory, SoftDeletes;

    protected $primaryKey = 'dependent_id';
    protected $fillable = [
        'emp_data_id',
        'name',
        'age',
        'relation',
        'address',
    ];

    public function empData(): BelongsTo
    {
        return $this->belongsTo(EmpData::class, 'emp_data_id', 'emp_data_id');
    }
}
