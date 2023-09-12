<?php

namespace App\Application\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
class ClinicResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this["id"],
            'name' => $this["name"],
            'consultants_count' => $this["consultants_count"],
        ];
    }
}
