<?php


namespace App\Application\Http\Requests;


use App\Rule\PeriodsNotIntersecting;
use App\Rule\TimeSheetNameExists;
use Illuminate\Foundation\Http\FormRequest;


class StoreTimeSheetRequest extends FormRequest
{
    // validation rules
    public function rules(): array
    {
        return [
            //shift data
            "name" => ['required', 'string', 'max:255', new TimeSheetNameExists()],

            //interval data
            "periods.*.start_time" => ['required', 'date_format:H:i', 'before:periods.*.end_time'],
            "periods.*.end_time" => ['required', 'date_format:H:i', 'after:periods.*.start_time'],
            'periods' => ['required', 'array', new PeriodsNotIntersecting()],
//            'periods.*' => [new TimeSheetNameExists()],

//            //shift-interval data
//            "interval_id" => ['required', 'integer', 'exists:intervals,interval_id'],
//            "shift_id" => ['required', 'integer', 'exists:shifts,shift_id'],

        ];
    }

}
