<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\DB;

class UniqueShiftForDate implements Rule
{

    protected $day_date , $shift_id;

    public function __construct($date , $shift_id)
    {
        $this->day_date = $date;
        $this->shift_id = $shift_id;
    }

    public function passes($attribute, $value): bool
    {
        return DB::table('work_days')
            ->where('day_date', $this->day_date)
            ->where('shift_id', $this->shift_id)
            ->Exists();
    }

    public function message(): string
    {
        return 'The selected shift is already assigned for this date.';
    }
}
