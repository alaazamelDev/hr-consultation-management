<?php

namespace App\Application\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property mixed job_vacancy_id
 * @property mixed dep_id
 * @property mixed name
 * @property mixed description
 * @property mixed count
 * @property mixed vacancy_status_id
 * @property mixed department
 * @property mixed vacancyStatus
 * @property mixed created_at
 */
class JobVacancyResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->job_vacancy_id,
            'department_id' => $this->dep_id,
            'department_name' => $this->department->name,
            'name' => $this->name,
            'description' => $this->description,
            'count' => $this->count,
            'vacancy_status_id' => $this->vacancy_status_id,
            'vacancy_status_name' => $this->vacancyStatus->name,
            'created_at' => $this->created_at,
        ];
    }
}
