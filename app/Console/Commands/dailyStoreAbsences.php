<?php

namespace App\Console\Commands;

use App\Domain\Services\AbsenceService;
use App\Domain\Services\HolidayService;
use App\Domain\Services\WorkingDayService;
use App\Infrastructure\Persistence\Eloquent\EloquentAbsenceRepository;
use App\Infrastructure\Persistence\Eloquent\EloquentHolidayRepository;
use App\Infrastructure\Persistence\Eloquent\EloquentWorkingDayRepository;
use Carbon\Carbon;
use Illuminate\Console\Command;

class dailyStoreAbsences extends Command
{
    protected $signature = 'absences:store';

    protected $description = 'store absences for employees';

    public function handle()
    {
        // Check if today is a Working day!
        $workingDayService = new WorkingDayService(new EloquentWorkingDayRepository());
        $days = $workingDayService->getWorkingDayList()->where('status', 1)->pluck('name');

        $date = Carbon::now()->toDateString();
        $dayName = Carbon::parse($date)->format('l');

        if ($days->contains($dayName)) {
            // Check if there is a holiday!
            $holidayService = new HolidayService(new EloquentHolidayRepository());
            $holiday = $holidayService->getHolidaysByDate($date);

            if ($holiday) {
                if ($holiday->is_recurring == 1) {
                    $date = Carbon::parse($holiday->date);
                    $updatedDate = $date->addYear();
                    $holiday->date = $updatedDate->toDateString();
                    $holiday->save();
                }
            }
            else {

                $absenceService = new AbsenceService(new EloquentAbsenceRepository());
                $emp_ids = $absenceService->storeAbsenceForEmployees($date);

                echo "employees with id's = {$emp_ids} are registered absent , " . now() . "\n";
            }
        }
    }
}
