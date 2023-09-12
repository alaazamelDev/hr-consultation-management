<?php

namespace App\Application\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Domain\Models\ShiftRequest;

class ShiftRequestResource extends JsonResource
{
    /**
     * @param Request $request
     * @return array
     */
    public function toArray(Request $request): array
    {
        return [
            'shift_req_id' => $this->shift_req_id,
            'emp_id' => $this->emp_id,
            'first_name' => $this->employee->first_name,
            'last_name' => $this->employee->last_name,
            'req_stat' => $this->req_stat,
            'description' => $this->description,
            'new_time_in' => $this->new_time_in,
            'new_time_out' => $this->new_time_out,
            'start_date' => $this->start_date,
            'duration' => $this->duration,
            'remaining_days' => $this->remaining_days,
            'current_job_title' => $this->employee->current_job_title
                ? $this->employee->current_job_title->name
                : null,
            'current_department' => $this->employee->current_department
                ? $this->employee->current_department->name
                : null,
        ];
    }
}
