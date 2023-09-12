<?php

namespace App\Application\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AddWorkDayRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'dates' => ['required', 'array'],
            'dates.*.date' => ['required', 'date'],
            'dates.*.shift_Id' => [
                'required',
                'exists:shifts,id',
            ],
        ];

    }
}
