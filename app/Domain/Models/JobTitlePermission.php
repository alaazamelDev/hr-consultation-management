<?php

namespace App\Domain\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class JobTitlePermission extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $primaryKey = 'job_title_perm_id';
    protected $fillable = ['job_title_id', 'perm_id'];

    public function jobTitle(): BelongsTo
    {
        return $this->belongsTo(JobTitle::class, 'job_title_id');
    }
}
