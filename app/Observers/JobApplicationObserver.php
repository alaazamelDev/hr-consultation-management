<?php

namespace App\Observers;

use App\Domain\Models\JobApplication;
use App\Domain\Models\Log;
use App\Domain\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class JobApplicationObserver
{
    /**
     * Handle the JobApplication "created" event.
     */
    public
    function created(JobApplication $jobApplication): void
    {
        //user
        $user_id = Auth::id();
        $user_name = User::query()->select('username')->find($user_id);

        //affected user
        $empData = $jobApplication->empData;
        $full_name = $empData?->full_name;

        // id_action = 1 , add job application request
        $this->logAction($user_id, 1, "Employee (Name: $user_name ) & (ID: $user_id ) Added a job application for (Name: $full_name).");
    }

    /**
     * Handle the JobApplication "updated" event.
     */
    public function updated(JobApplication $jobApplication): void
    {

        $user_id = Auth::id();
        $user_name = User::query()->select('username')->find($user_id);


        $empData = $jobApplication->empData;
        $full_name = $empData?->full_name;

        $updatedFields = $jobApplication->getDirty();

        if (array_key_exists('app_status_id', $updatedFields)) {
            if ($updatedFields['app_status_id'] == 2) {
                $this->logAction($user_id, 2, "Employee (Name: $user_name ) & (ID: $user_id ) Accepted a job application for (Name: $full_name).");
            }

            if ($updatedFields['app_status_id'] == 4) {
                $this->logAction($user_id, 3, "Employee (Name: $user_name ) & (ID: $user_id ) Rejected a job application for (Name: $full_name).");
            }
        } else {
            $this->logAction($user_id, 4, "Employee (Name: $user_name ) & (ID: $user_id ) Updated a specific field in the job application for (Name: $full_name).");
        }
    }


    private function logAction(int $user_id, int $action, string $description): void
    {
        $logData = [
            'user_id' => $user_id,
            'action_id' => $action,
            'description' => $description,
            'date' => Carbon::now(),
        ];

        Log::query()->create($logData);
    }
}
