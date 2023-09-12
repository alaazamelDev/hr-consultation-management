<?php

namespace App\Application\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property mixed dep_id
 * @property mixed name
 * @property mixed description
 * @property mixed employees_count
 */
class DepartmentResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'dep_id' => $this->dep_id,
            'name' => $this->name,
            'description' => $this->description,
            'employees_count' => $this->employees_count,
        ];
    }
}
