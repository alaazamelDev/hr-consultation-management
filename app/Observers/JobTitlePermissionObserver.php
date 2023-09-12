<?php

namespace App\Observers;


use App\Domain\Models\JobTitlePermission;
use App\Domain\Models\Log;
use App\Domain\Models\User;
use Illuminate\Support\Facades\Auth;

class JobTitlePermissionObserver
{
    /**
     * Handle the JobTitlePermission "created" event.
     */
    public function created(JobTitlePermission $jobTitlePermission): void
    {

        $user_id = Auth::id();
        $user_name = User::query()->select('username')->find($user_id);

        $this->logAction($user_id, 21, "Employee (Name: $user_name ) & (ID: $user_id ) Added a new permission to a Job Title .");
    }

    public function deleted(JobTitlePermission $jobTitlePermission): void
    {
        $user_id = Auth::id();
        $user_name = User::query()->select('username')->find($user_id);

        $this->logAction($user_id, 22, "Employee (Name: $user_name ) & (ID: $user_id ) Deleted a new permission to a Job Title .");
    }

    private function logAction(int $user_id, int $action, string $description): void
    {
        Log::query()->create([
            'user_id' => $user_id,
            'action_id' => $action,
            'description' => $description,
            'date' => now(),
        ]);
    }


}
