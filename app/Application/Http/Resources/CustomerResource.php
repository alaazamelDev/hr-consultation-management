<?php

namespace App\Application\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Storage;

class CustomerResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this["id"],
            'first_name' => $this["first_name"],
            'last_name' => $this["last_name"],
            'education_level' => [
                'education_level_id' => $this["educationLevel"]["education_level_id"],
                'name' => $this["educationLevel"]["name"],
            ],
            'email' => $this["email"],
            'username' => $this["username"],
            'job' => $this["job"],
            'birth_date' => $this["birth_date"],
            'phone' => $this["phone"] ?? null,
            'phone_number' => $this["phone_number"],
            'martial_status' => $this["martial_status"],
            'num_of_children' => $this["num_of_children"],
            'national_number' => $this["national_number"],
            'profile_picture' => $this["profile_picture"] == null
                ? null
                : Storage::url($this["profile_picture"]),

            'verified' => $this["verified"] ?? null,
            'blocked' => $this["blocked"] ?? null,
            'isUsingApp' => $this["isUsingApp"] ?? false,

            'created_at' => $this["created_at"]->format('Y-m-d_H:i:s'),
        ];
    }
}
