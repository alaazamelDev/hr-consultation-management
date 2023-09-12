<?php


namespace App\Application\Http\Resources;


use Illuminate\Http\Resources\Json\JsonResource;

class RelativeEmployeesResource extends JsonResource
{

    public function toArray($request): array
    {
        return [
            'emp_id' => $this->emp_id,
            'full_name' => $this->full_name,
        ];
    }
}
