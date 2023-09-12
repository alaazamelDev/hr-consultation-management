<?php

namespace App\Application\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property mixed $job_title_id
 * @property mixed name
 * @property mixed description
 * @property mixed employees_count
 * @property mixed deleted_at
 * @property mixed permissions
 */
class JobTitleResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'job_title_id' => $this->job_title_id,
            'name' => $this->name,
            'description' => $this->description,
            'employees_count' => $this->employees_count,
            'deleted_at' => $this->deleted_at,
            'permissions' => PermissionResource::collection($this->permissions)
        ];
    }
}
