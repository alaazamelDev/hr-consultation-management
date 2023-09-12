<?php

namespace App\Observers;


use App\Domain\Models\JobTitle;
use App\Domain\Models\Log;
use App\Domain\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class JobTitleObserver
{
    /**
     * Handle the JobTitle "created" event.
     */
    public function created(JobTitle $jobTitle): void
    {
        $user_id = Auth::id();
        $user_name = User::query()->select('username')->find($user_id);

        $this->logAction($user_id, "Employee (Name: $user_name ) & (ID: $user_id ) create a new Job Title ($jobTitle->name).");
    }

    private function logAction(int $user_id, string $description): void
    {
        Log::query()->create([
            'user_id' => $user_id,
            'action_id' => 20,
            'description' => $description,
            'date' => Carbon::now(),
        ]);
    }

}
