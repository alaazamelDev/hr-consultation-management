<?php

namespace App\Observers;


use App\Domain\Models\AffectedUser;
use App\Domain\Models\Log;
use App\Domain\Models\StaffPermission;
use App\Domain\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class StaffPermissionObserver
{
    /**
     * Handle the StaffPermission "created" event.
     */
    public function created(StaffPermission $staffPermission): void
    {
        // user
        $user_id = Auth::id();
        $user_name = User::query()->select('username')->find($user_id);

        //affected user
        $full_name = null;
        $affected_user_id = (int)null;
        $staffing = $staffPermission->staffing;
        if (isset($staffing)) {
            $employee = $staffing->employee;
            $full_name = $employee->full_name;
            $affected_user_id = $employee->user->user_id;
        }


        $this->logAction($user_id, 23,
            "Employee (Name: $user_name ) & (ID: $user_id ) added a new permission for (Name: $full_name ) & (ID: $affected_user_id )."
            , $affected_user_id);
    }


    /**
     * Handle the StaffPermission "deleted" event.
     */
    public
    function deleted(StaffPermission $staffPermission): void
    {
        // user
        $user_id = Auth::id();
        $user_name = User::query()->select('username')->find($user_id);

        //affected user
        $full_name = null;
        $affected_user_id = (int)null;
        $staffing = $staffPermission->staffing;
        if (isset($staffing)) {
            $employee = $staffing->employee;
            $full_name = $employee->full_name;
            $affected_user_id = $employee->user->user_id;
        }

        $this->logAction($user_id, 24,
            "Employee (Name: $user_name ) & (ID: $user_id ) deleted a permission for (Name: $full_name ) & (ID: $affected_user_id )."
            , $affected_user_id);
    }

    private function logAction(int $user_id, int $action, string $description, int $affected_user_id): void
    {
        try {
            // start transaction
            DB::beginTransaction();
            $logData = [
                'user_id' => $user_id,
                'action_id' => $action,
                'description' => $description,
                'date' => Carbon::now(),
            ];

            $log = Log::query()->create($logData);

            $affected_user = [
                'user_id' => $affected_user_id,
                'log_id' => $log->log_id
            ];
            AffectedUser::query()->create($affected_user);

            DB::commit();
        } catch (Exception $exception) {
            DB::rollBack();
        }
    }

}
