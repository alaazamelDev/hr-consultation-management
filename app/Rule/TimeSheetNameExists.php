<?php

namespace App\Rule;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\DB;

class TimeSheetNameExists implements Rule
{
    public function passes($attribute, $value): bool
    {
//        // Assuming $value is an array containing "start_time" and "end_time" keys
//        $startTime = $value['start_time'];
//        $endTime = $value['end_time'];
//
//        // Check if the specific time interval exists in the database as a single record
//        // Return true if the interval exists as a single record, false otherwise
//        return !(DB::table('intervals')
//            ->where('start_time', $startTime)
//            ->where('end_time', $endTime)
//            ->exists());

//        $name = $value['name'];
        $name = trim($value);
        $name = strtolower($name);

        return !(DB::table('shifts')->whereRaw('LOWER(name) = ?', [$name])->exists());
    }

    public function message(): string
    {
        return 'The name is already EXISTS in the database.';
    }


}
