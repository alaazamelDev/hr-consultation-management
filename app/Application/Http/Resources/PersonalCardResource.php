<?php


namespace App\Application\Http\Resources;


use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property mixed personal_card_id
 * @property mixed card_number
 * @property mixed place_of_issue
 * @property mixed date_of_issue
 */
class PersonalCardResource extends JsonResource
{

    public function toArray(Request $request)
    {
        return [
            'id' => $this->personal_card_id,
            'card_number' => $this->card_number,
            'card_place_of_issue' => $this->place_of_issue,
            'card_date_of_issue' => $this->date_of_issue,
        ];
    }
}
