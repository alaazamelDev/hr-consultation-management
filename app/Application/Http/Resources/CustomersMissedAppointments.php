<?php

namespace App\Application\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CustomersMissedAppointments extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'=>$this->id,
            'full_name' => $this->full_name,
            'username' => $this->username,
            'phone_number' => $this->phone_number,
            'missed_appointment_count' => $this->missed_appointment_count,
            'verified' => $this->verified,
            'blocked' => $this->blocked,
        ];
    }
}
