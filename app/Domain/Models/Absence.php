<?php

namespace App\Domain\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Absence extends Model
{
    use HasFactory ;
//    use SoftDeletes;

    protected $primaryKey = 'absence_id';
    protected $fillable = ['emp_id', 'absence_date','status','absence_status_id'];

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'emp_id', 'emp_id');
    }

    public function absenceStatus(): BelongsTo
    {
        return $this->belongsTo(AbsenceStatus::class, 'absence_status_id', 'absence_status_id');
    }

}
