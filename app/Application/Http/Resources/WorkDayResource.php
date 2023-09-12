<?php

namespace App\Application\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class WorkDayResource extends JsonResource
{


    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'shift_id' => $this->shift_id,
            'day_date' => $this->day_date,
            'day_name' => Carbon::parse($this->day_date)->format('l'),
            'appointment' => AppointmentResource::collection($this->appointments)
        ];
    }
}
