<?php

namespace App\Domain\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Permission extends Model
{
    use HasFactory;
    protected $primaryKey = 'perm_id';
    protected $fillable = ['name', 'description'];
    public function staffings(): BelongsToMany
    {
        return $this->belongsToMany(Staffing::class,'staff_permissions','perm_id','staff_id',
            'perm_id','staff_id')
            ->withPivot('status');
    }

    public function jobTitles(): BelongsToMany
    {
        return $this->belongsToMany(JobTitle::class,'job_title_permissions','perm_id','job_title_id',
            'perm_id','job_title_id');
    }
}
