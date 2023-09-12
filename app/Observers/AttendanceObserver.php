<?php

namespace App\Observers;


use App\Domain\Models\Attendance;
use App\Domain\Models\Log;
use Carbon\Carbon;

class AttendanceObserver
{
    /**
     * Handle the Attendance "created" event.
     */
    public function created(Attendance $attendance): void
    {
        $full_name = null;
        $user_id = (int)null;
        $employee = $attendance->employee;
        if (isset($employee)) {
            $full_name = $employee->full_name;
            $user_id = $employee->user->user_id;
        }

        $this->logAction($user_id, "Employee (Name: $full_name ) & (ID: $user_id ) made an attendance.");
    }

    private function logAction(int $user_id, string $description): void
    {
        Log::query()->create([
            'user_id' => $user_id,
            'action_id' => 8,
            'description' => $description,
            'date' => Carbon::now(),
        ]);

    }

}
