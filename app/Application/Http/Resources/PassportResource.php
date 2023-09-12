<?php


namespace App\Application\Http\Resources;


use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property mixed passport_id
 * @property mixed passport_number
 * @property mixed place_of_issue
 * @property mixed date_of_issue
 */
class PassportResource extends JsonResource
{

    public function toArray(Request $request)
    {
        return [
            'passport_id' => $this->passport_id,
            'passport_number' => $this->passport_number,
            'passport_place_of_issue' => $this->place_of_issue,
            'passport_date_of_issue' => $this->date_of_issue,
        ];
    }
}
