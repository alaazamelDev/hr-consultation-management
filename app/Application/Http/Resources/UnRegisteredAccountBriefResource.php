<?php

namespace App\Application\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UnRegisteredAccountBriefResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this['id'],
            'name' => $this['name'],
            'phone_number' => $this['phone_number'],
            'created_at' => $this['created_at'],
        ];
    }
}
