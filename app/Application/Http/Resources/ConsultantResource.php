<?php

namespace App\Application\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ConsultantResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this["id"],
            'first_name' => $this["first_name"],
            'last_name' => $this["last_name"],
            'clinic_id' => $this["clinic_id"],
            'birth_date' => $this["birth_date"],
            'phone_number' => $this["phone_number"],
            'address' => $this["address"],
            'user' => [
                'user_id' => $this["user"]["user_id"],
                'user_email' => $this["user"]["email"],
                'user_username' => $this["user"]["username"], // TODO Delete it
            ]
        ];
    }
}
