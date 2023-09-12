<?php

namespace App\Application\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property mixed schedule_id
 * @property mixed name
 * @property mixed time_in
 * @property mixed time_out
 */
class ScheduleResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'schedule_id' => $this->schedule_id,
            'name' => $this->name,
            'time_in' => $this->time_in,
            'time_out' => $this->time_out,
        ];
    }
}
