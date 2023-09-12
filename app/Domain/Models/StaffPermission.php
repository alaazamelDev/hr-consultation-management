<?php

namespace App\Domain\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class StaffPermission extends Model
{
    use HasFactory, SoftDeletes;

    protected $primaryKey = 'staff_perm_id';
    protected $fillable = ['staff_id', 'perm_id', 'status'];

    public function staffing(): BelongsTo
    {
        return $this->belongsTo(Staffing::class,'staff_id');
    }
}
