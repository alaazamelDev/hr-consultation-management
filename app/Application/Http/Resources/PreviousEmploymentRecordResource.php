<?php


namespace App\Application\Http\Resources;


use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property mixed prev_emp_record_id
 * @property mixed employer_name
 * @property mixed address
 * @property mixed telephone
 * @property mixed job_title
 * @property mixed job_description
 * @property mixed start_date
 * @property mixed end_date
 * @property mixed salary
 * @property mixed allowance
 * @property mixed quit_reason
 */
class PreviousEmploymentRecordResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'prev_emp_record_id' => $this->prev_emp_record_id,
            'employer_name' => $this->employer_name,
            'address' => $this->address,
            'telephone' => $this->telephone,
            'job_title' => $this->job_title,
            'job_description' => $this->job_description,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'salary' => $this->salary,
            'allowance' => $this->allowance,
            'quit_reason' => $this->quit_reason,
        ];
    }
}
