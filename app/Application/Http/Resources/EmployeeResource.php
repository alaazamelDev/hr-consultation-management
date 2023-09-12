<?php

namespace App\Application\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EmployeeResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'emp_id' => $this->emp_id,
            'username' => $this->user->username,
            'email' => $this->user->email,
            'employment_status' => new EmploymentStatusResource($this->current_employment_status),
        ];
    }
}
