<?php


namespace App\Application\Http\Resources;


use App\Domain\Models\EmpData;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property mixed relative_id
 * @property EmpData relativeData
 */
class RelativeResource extends JsonResource
{
    public function toArray(Request $request)
    {
        return [
            'relative_id' => $this->relative_id,
            'relative_data_id' => $this->relativeData->emp_data_id,
            'relative_first_name' => $this->relativeData->first_name,
            'relative_last_name' => $this->relativeData->last_name,
        ];
    }

}
