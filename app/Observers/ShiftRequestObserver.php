<?php

namespace App\Observers;


use App\Domain\Models\AffectedUser;
use App\Domain\Models\Log;
use App\Domain\Models\ShiftRequest;
use App\Domain\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ShiftRequestObserver
{
    /**
     * Handle the ShiftRequest "created" event.
     */
    public function created(ShiftRequest $shiftRequest): void
    {
        $employee = $shiftRequest->employee;
        $full_name = $employee->full_name;
        $user_id = $employee->user->user_id;

        $this->logAction($user_id, 15,
            "Employee (Name: $full_name ) & (ID: $user_id ) made a Shift request."
            , (int)null);
    }

    /**
     * Handle the ShiftRequest "updated" event.
     */
    public
    function updated(ShiftRequest $shiftRequest): void
    {

        $user_id = Auth::id();
        $user_name = User::query()->select('username')->find($user_id);

        $employee = $shiftRequest->employee;
        $full_name = $employee->full_name;
        $affected_user_id = $employee->user->user_id;

        $updatedFields = $shiftRequest->getDirty();

        if (array_key_exists('req_stat', $updatedFields)) {
            if ($updatedFields['req_stat'] == 2) {
                $this->logAction($user_id, 16,
                    "Employee (Name: $user_name ) & (ID: $user_id ) Accepted a shift request for the Employee (Name: $full_name) & (ID: $affected_user_id )."
                    , $affected_user_id);
            }

            if ($updatedFields['req_stat'] == 3) {
                $this->logAction($user_id, 17,
                    "Employee (Name: $user_name ) & (ID: $user_id ) Rejected a shift request for the Employee (Name: $full_name) & (ID: $affected_user_id )."
                    , $affected_user_id);
            }
        } else {
            $this->logAction($affected_user_id, 18, "Employee (Name: $full_name ) & (ID: $affected_user_id ) Updated a specific field in the shift request."
                , (int)null);
        }
    }

    /**
     * Handle the ShiftRequest "deleted" event.
     */
    public
    function deleted(ShiftRequest $shiftRequest): void
    {
        $employee = $shiftRequest->employee;
        $full_name = $employee->full_name;
        $user_id = $employee->user->user_id;

        $this->logAction($user_id, 19,
            "Employee (Name: $full_name ) & (ID: $user_id ) Deleted the shift request."
            , (int)null);
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

            if ($action == 16 || $action == 17) {
                $affected_user = [
                    'user_id' => $affected_user_id,
                    'log_id' => $log->log_id
                ];
                AffectedUser::query()->create($affected_user);
            }
            DB::commit();
        } catch (Exception $exception) {
            DB::rollBack();
        }
    }
}
