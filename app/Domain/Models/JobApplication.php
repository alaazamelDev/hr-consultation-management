<?php

namespace App\Domain\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class JobApplication
 * @package App\Domain\Models
 *
 * @property int job_app_id
 * @property int app_status_id
 * @property int job_vacancy_id
 * @property int emp_data_id
 * @property string section_man_notes
 * @property string vice_man_rec
 *
 * @property ApplicationStatus applicationStatus
 * @property JobVacancy jobVacancy
 * @property EmpData empData
 * @property mixed full_name
 */
class JobApplication extends Model
{
    use HasFactory, SoftDeletes;

    protected $primaryKey = 'job_app_id';
    protected $fillable = ['app_status_id', 'job_vacancy_id', 'emp_data_id', 'section_man_notes', 'vice_man_rec'];

    public function applicationStatus(): BelongsTo
    {
        return $this->belongsTo(ApplicationStatus::class, 'app_status_id', 'app_status_id');
    }

    public function jobVacancy(): BelongsTo
    {
        return $this->belongsTo(JobVacancy::class, 'job_vacancy_id', 'job_vacancy_id');
    }

    public function empData(): BelongsTo
    {
        return $this->belongsTo(EmpData::class, 'emp_data_id', 'emp_data_id');
    }

    public function employee(): HasOne
    {
        return $this->hasOne(Employee::class, 'job_app_id', 'job_app_id');
    }
}
