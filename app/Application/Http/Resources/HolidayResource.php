<?php

namespace App\Application\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Domain\Models\Holiday;

class HolidayResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'holiday_id' => $this['holiday_id'],
            'name' => $this['name'],
            'date' => $this['date'],
            'is_recurring' => $this['is_recurring']
        ];
    }
}
