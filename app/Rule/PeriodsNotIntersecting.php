<?php

namespace App\Rule;

use Illuminate\Contracts\Validation\Rule;

class PeriodsNotIntersecting implements Rule
{

    public function passes($attribute, $value): bool
    {
        $periods = $value;
        $periodCount = count($periods);

        for ($i = 0; $i < $periodCount - 1; $i++) {
            $startTime1 = $periods[$i]['start_time'];
            $endTime1 = $periods[$i]['end_time'];

            for ($j = $i + 1; $j < $periodCount; $j++) {
                $startTime2 = $periods[$j]['start_time'];
                $endTime2 = $periods[$j]['end_time'];

                if (($startTime1 >= $startTime2 && $startTime1 < $endTime2) || ($endTime1 > $startTime2 && $endTime1 <= $endTime2)) {
                    return false; // Periods overlap with each other
                }
            }
        }

        return true; // No intersections found, validation passes
    }

    public function message(): string
    {
        return 'The periods are intersecting with each other.';
    }
}
