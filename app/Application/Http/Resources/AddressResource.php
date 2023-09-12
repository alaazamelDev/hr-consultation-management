<?php


namespace App\Application\Http\Resources;


use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property mixed address_id
 * @property mixed state
 * @property mixed city
 * @property mixed street
 * @property mixed postal_code
 * @property mixed email
 * @property mixed mobile_no
 * @property mixed home_phone_no
 * @property mixed work_phone_no
 */
class AddressResource extends JsonResource
{
    public function toArray(Request $request)
    {
        return [
            'address_id' => $this->address_id,
            'state' => $this->state,
            'city' => $this->city,
            'street' => $this->street,
            'postal_code' => $this->postal_code,
            'email' => $this->email,
            'mobile_no' => $this->mobile_no,
            'home_phone_no' => $this->home_phone_no,
            'work_phone_no' => $this->work_phone_no,
        ];
    }
}
