<?php

namespace App\Domain\Models;

use App\Observers\VacationRequestObserver;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VacationRequest extends Model
{
    use HasFactory;

    protected $primaryKey = 'vacation_req_id';
    protected $fillable = [
        'emp_id',
        'req_stat',
        'description',
        'start_date',
        'duration'
    ];

    protected $dispatchesEvents = [
        'created' => VacationRequestObserver::class,
        'creating'  => VacationRequestObserver::class,
        'updated' => VacationRequestObserver::class,
        'updating' => VacationRequestObserver::class,
        'deleted' => VacationRequestObserver::class,
        'deleting' => VacationRequestObserver::class,
        'saved'    => VacationRequestObserver::class,
    ];

    protected $casts = [
        'duration' => 'integer',
        'start_date' => 'datetime:Y-m-d',
    ];

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'emp_id', 'emp_id');
    }

}
