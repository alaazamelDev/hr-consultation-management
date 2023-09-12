<?php

namespace App\Application\Http\Resources;

use App\Domain\Models\JobTitle;
use App\Domain\Models\Permission;
use App\Domain\Models\Staffing;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property mixed $emp_id
 * @property mixed $user_id
 * @property Staffing[] staffings
 * @property JobTitle job_title
 * @property Permission[] permissions
 */
class EmployeeJobTitleResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            // employee data
            'emp_id' => $this->emp_id,
            'user_id' => $this->user_id,

            // job title & permissions data
            'job_title' => [
                'job_title_id' => $this->staffings[0]->jobTitle->job_title_id,
                'name' => $this->staffings[0]->jobTitle->name,
                'description' => $this->staffings[0]->jobTitle->description,
                'job_title_permissions' => [
                    'perm_id' => $this->staffings[0]->jobTitle->permissions[0]->perm_id,
                    'name' => $this->staffings[0]->jobTitle->permissions[0]->name,
                    'description' => $this->staffings[0]->jobTitle->permissions[0]->description,
                    'permissions' => PermissionResource::collection($this->staffings[0]->jobTitle->permissions)
                ],
                'other_permissions' => PermissionResource::collection($this->staffings[0]->permissions)
            ],
        ];
    }

}
