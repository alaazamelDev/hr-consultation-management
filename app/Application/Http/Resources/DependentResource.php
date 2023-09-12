<?php


namespace App\Application\Http\Resources;


use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property integer dependent_id
 * @property string name
 * @property integer age
 * @property string relation
 * @property string address
 */
class DependentResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'dependent_id' => $this->dependent_id,
            'name' => $this->name,
            'age' => $this->age,
            'relationship' => $this->relation,
            'address' => $this->address,
        ];
    }
}
