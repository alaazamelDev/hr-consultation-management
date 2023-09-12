<?php

namespace Database\Seeders;

use App\Domain\Models\WorkingDay;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class WorkingDaysSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // create records represents
        // all weekdays in a week (in Arabic)
        $days = [
            'الأحد',
            'الاثنين',
            'الثلاثاء',
            'الأربعاء',
            'الخميس',
            'الجمعة',
            'السبت',
        ];

        // loop through days array
        foreach ($days as $day) {
            // create a new record in working_days table
            WorkingDay::query()->create([
                'name' => $day,
                'status' => 1,
            ]);
        }
    }
}
