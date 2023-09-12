<?php

namespace App\Application\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Domain\Models\CD\Consultant;

class ConsultantBriefResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this["id"],
            'first_name' => $this["first_name"],
            'last_name' => $this["last_name"],
            'clinic_id' => $this["clinic_id"],
            'phone_number' => $this["phone_number"],
            'user_email'=> $this["user"]["email"],
        ];
    }
}
