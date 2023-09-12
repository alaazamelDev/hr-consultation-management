<?php

namespace App\Observers;


use App\Domain\Models\Leave;
use App\Domain\Models\Log;
use Carbon\Carbon;

class LeaveObserver
{
    /**
     * Handle the Leave "created" event.
     */
    public function created(Leave $leave): void
    {
        $full_name = null;
        $user_id = (int)null;
        $employee = $leave->employee;
        if (isset($employee)) {
            $full_name = $employee->full_name;
            $user_id = $employee->user->user_id;
        }

        $this->logAction($user_id, "Employee (Name: $full_name ) & (ID: $user_id ) made a leave.");
    }

    private function logAction(int $user_id, string $description): void
    {
        Log::query()->create([
            'user_id' => $user_id,
            'action_id' => 9,
            'description' => $description,
            'date' => Carbon::now(),
        ]);
    }

}
