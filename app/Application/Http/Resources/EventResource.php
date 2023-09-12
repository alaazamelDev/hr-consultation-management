<?php

namespace App\Application\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class EventResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this['id'],
            'title' => $this['title'],
            'address' => $this['address'],
            'side_address' => $this['side_address'] ?? null,
            'description' => $this['description'] ?? null,
            'link' => $this['link'] ?? null,
            'image' => Storage::url($this['image']),
            'blurhash' => $this['blurhash'] ?? null,
            'start_date' => $this['start_date'],
            'end_date' => $this['end_date'] ?? null,
        ];
    }
}
