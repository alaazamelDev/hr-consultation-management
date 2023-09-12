<?php

namespace App\Domain\Models;

use App\Domain\Models\CD\Customer;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class EducationLevel extends Model
{
    use HasFactory;
    protected $primaryKey = 'education_level_id';

    protected $fillable = [
        'name',
    ];

    public function empData(): BelongsToMany
    {
        return $this->belongsToMany(EmpData::class, 'education_level_emp_data', 'education_level_id', 'emp_data_id',
            'education_level_id','emp_data_id')
            ->withPivot('univ_name', 'city', 'start_date', 'end_date', 'specialize', 'grade');
    }

    public function customers(): HasMany
    {
        return $this->hasMany(Customer::class,'education_level_id','education_level_id');
    }
}
