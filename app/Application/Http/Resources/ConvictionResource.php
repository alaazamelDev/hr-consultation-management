<?php


namespace App\Application\Http\Resources;


use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property mixed conviction_id
 * @property mixed description
 */
class ConvictionResource extends JsonResource
{
    public function toArray(Request $request)
    {
        return [
            'conviction_id' => $this->conviction_id,
            'description' => $this->description,
        ];
    }
}
