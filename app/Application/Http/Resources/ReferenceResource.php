<?php


namespace App\Application\Http\Resources;


use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property mixed reference_id
 * @property mixed name
 * @property mixed job
 * @property mixed company
 * @property mixed telephone
 * @property mixed address
 */
class ReferenceResource extends JsonResource
{
    public function toArray(Request $request)
    {
        return [
            'reference_id' => $this->reference_id,
            'name' => $this->name,
            'job' => $this->job,
            'company' => $this->company,
            'telephone' => $this->telephone,
            'address' => $this->address,
        ];
    }
}
