<?php


namespace App\Application\Http\Resources;


use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property mixed app_status_id
 * @property mixed name
 */
class ApplicationStatusResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'app_status_id' => $this->app_status_id,
            'name' => $this->name,
        ];
    }

}
