<?php


namespace App\Application\Http\Resources;


use Illuminate\Http\Resources\Json\JsonResource;

class EmployeePermissionResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'perm_id' => $this->perm_id,
            'name' => $this->name,
            'type' => $this->type,
        ];
    }
}
