<?php


namespace App\Application\Http\Resources;


use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use JetBrains\PhpStorm\Pure;

/**
 * @property mixed emp_id
 * @property mixed user
 * @property mixed job_app_id
 * @property mixed schedule_id
 * @property mixed job_title_id
 * @property mixed start_date
 * @property mixed start_working_date
 * @property mixed current_employment_status
 * @property mixed current_job_title
 */
class EmployeeBriefResource extends JsonResource
{

    #[Pure] public function toArray(Request $request): array
    {
        return [
            'emp_id' => $this->emp_id,
            'full_name' => $this->full_name,
            'department_name' => $this->current_department?->name,
            'job_title_name' => $this->current_job_title?->name,
            'current_employment_status' => new EmploymentStatusResource($this->current_employment_status),
            'schedule' => new ScheduleResource($this->schedule),
        ];
    }

}
