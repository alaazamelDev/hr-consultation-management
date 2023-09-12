<?php

namespace App\Application\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Domain\Models\WorkingDay;

class WorkingDayResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            "working_day_id" => $this["working_day_id"],
            "name" => $this["name"],
            "status" => $this["status"]
        ];
    }
}
