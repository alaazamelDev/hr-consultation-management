<?php

namespace App\Application\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Domain\Models\CD\UnRegisteredAccount;

class UnRegisteredAccountResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this['appointment']['id'],
            'work_day_id' => $this['appointment']['work_day_id'],
            'status_id' => $this['appointment']['status_id'],
            'start_time' => $this['appointment']['start_time'],
            'end_time' => $this['appointment']['end_time'],
            'date' => $this->appointment->workDay->day_date,
            'cancellation_reason' => $this['appointment']['cancellation_reason'],
            'created_at' => $this['appointment']['created_at'],

            'status'=>[
                'id' => $this['appointment']['status']['id'],
                'name' => $this['appointment']['status']['name'],
                'un-registered-account_id' => $this['id'],
                'customer_name' => $this['name'],
                'phone_number' => $this['phone_number'],
                'created_at' => $this['created_at']->format('Y-m-d_H:i:s'),
            ],
            'case_note' => $this->appointment->caseNote ? [
                'id' => $this->appointment->caseNote->id,
                'app_id' => $this->appointment->caseNote->app_id,
                'title' => $this->appointment->caseNote->title,
                'description' => $this->appointment->caseNote->description,
                'created_at' => $this->appointment->caseNote->created_at,
            ] : null,

            'clinic_name' => $this->appointment->getClinicName(),
            'consultant_name' => $this->appointment->getConsultantName(),
        ];
    }
}
