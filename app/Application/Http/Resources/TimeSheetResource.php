<?php

namespace App\Application\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TimeSheetResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'consultant_id' => $this->consultant_id,
            // get work days with appointment
            'work_days' => WorkDayResource::collection($this->workDays),
        ];
    }
}
