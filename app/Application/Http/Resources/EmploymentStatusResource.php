<?php


namespace App\Application\Http\Resources;


use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property mixed emp_status_id
 * @property mixed name
 * @property mixed description
 */
class EmploymentStatusResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'emp_status_id' => $this->emp_status_id,
            'name' => $this->name,
            'description' => $this->description,
        ];
    }
}
