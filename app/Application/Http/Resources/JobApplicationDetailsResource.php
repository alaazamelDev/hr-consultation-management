<?php


namespace App\Application\Http\Resources;


use Illuminate\Http\Resources\Json\JsonResource;
use JetBrains\PhpStorm\Pure;

/**
 * @property mixed job_app_id
 * @property mixed applicationStatus
 * @property mixed section_man_notes
 * @property mixed vice_man_rec
 * @property mixed empData
 * @property mixed jobVacancy
 */
class JobApplicationDetailsResource extends JsonResource
{
    #[Pure] public function toArray($request): array
    {
        return [

            // Job application data
            'job_application' => [
                'id' => $this->job_app_id,
                'status' => new ApplicationStatusResource($this->applicationStatus),
                'section_man_notes' => $this->section_man_notes,
                'vice_man_rec' => $this->vice_man_rec,
                'job_vacancy' => new JobVacancyResource($this->jobVacancy)
            ],

            // employee id field is null when the job application is not used by an employee
            // otherwise, it is the employee id of the employee who used the job application
            'emp_id' => $this->employee ? $this->employee->emp_id : null,

            // Employee data
            'employee_data' => new EmployeeDataResource($this->empData),

            // Application status data
            'application_status' => new ApplicationStatusResource($this->applicationStatus),
        ];
    }

}
