<?php

namespace App\Application\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Domain\Models\VacationRequest;

/**
 * @property mixed vacation_req_id
 * @property mixed emp_id
 * @property mixed employee
 * @property mixed req_stat
 * @property mixed description
 * @property mixed start_date
 * @property mixed duration
 */
class VacationRequestResource extends JsonResource
{

    public function toArray(Request $request): array
    {
        return [
            'vacation_req_id' => $this->vacation_req_id,
            'emp_id' => $this->emp_id,
            'first_name' => $this->employee->first_name,
            'last_name' => $this->employee->last_name,
            'req_stat' => $this->req_stat,
            'description' => $this->description,
            'start_date' => $this->start_date,
            'duration' => $this->duration,
            'current_job_title' => $this->employee->current_job_title
                ? $this->employee->current_job_title->name
                : null,
            'current_department' => $this->employee->current_department
                ? $this->employee->current_department->name
                : null,
        ];
    }
}
