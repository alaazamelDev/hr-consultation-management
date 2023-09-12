<?php


namespace App\Application\Http\Resources;


use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property mixed driving_licence_id
 * @property mixed category
 * @property mixed number
 * @property mixed date_of_issue
 * @property mixed place_of_issue
 * @property mixed expiry_date
 * @property mixed blood_group
 */
class DrivingLicenseResource extends JsonResource
{

    public function toArray(Request $request)
    {
        return [
            'driving_licence_id' => $this->driving_licence_id,
            'category' => $this->category,
            'number' => $this->number,
            'date_of_issue' => $this->date_of_issue,
            'place_of_issue' => $this->place_of_issue,
            'expiry_date' => $this->expiry_date,
            'blood_group' => $this->blood_group,
        ];
    }
}
