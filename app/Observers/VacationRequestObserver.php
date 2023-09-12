<?php

namespace App\Observers;


use App\Domain\Models\AffectedUser;
use App\Domain\Models\Log;
use App\Domain\Models\User;
use App\Domain\Models\VacationRequest;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class VacationRequestObserver
{
    /**
     * Handle the VacationRequest "created" event.
     */
    public function created(VacationRequest $vacationRequest): void
    {
        $employee = $vacationRequest->employee;
        $full_name = $employee->full_name;
        $user_id = $employee->user->user_id;

        $this->logAction($user_id, 10,
            "Employee (Name: $full_name ) & (ID: $user_id ) made a vacation request."
            , (int)null);
    }

    /**
     * Handle the VacationRequest "updated" event.
     */
    public function updated(VacationRequest $vacationRequest): void
    {
        $user_id = Auth::id();
        $user_name = User::query()->select('username')->find($user_id);

        $employee = $vacationRequest->employee;
        $full_name = $employee->full_name;
        $affected_user_id = $employee->user->user_id;

        $updatedFields = $vacationRequest->getDirty();

        if (array_key_exists('req_stat', $updatedFields)) {
            if ($updatedFields['req_stat'] == 2) {
                $this->logAction($user_id, 11,
                    "User (Name: $user_name ) & (ID: $user_id ) Accepted a vacation request for the Employee (Name: $full_name) & (ID: $affected_user_id )."
                    , $affected_user_id);
            }

            if ($updatedFields['req_stat'] == 3) {
                $this->logAction($user_id, 12,
                    "User (Name: $user_name ) & (ID: $user_id ) Rejected a vacation request for the Employee (Name: $full_name) & (ID: $affected_user_id )."
                    , $affected_user_id);
            }
        } else {
            $this->logAction($affected_user_id, 13, "Employee (Name: $full_name ) & (ID: $affected_user_id ) Updated a specific field in the vacation request."
                , (int)null);
        }
    }

    /**
     * Handle the VacationRequest "deleted" event.
     */
    public function deleted(VacationRequest $vacationRequest): void
    {
        $employee = $vacationRequest->employee;
        $full_name = $employee->full_name;
        $user_id = $employee->user->user_id;


        $this->logAction($user_id, 14, "Employee (Name: $full_name ) & (ID: $user_id ) Deleted the vacation request."
            , (int)null);

    }


    private function logAction(int $user_id, int $action, string $description, int $affected_user_id): void
    {
//        $user_id = Auth::id();

        try {
            // start transaction
            DB::beginTransaction();
            $log = Log::query()->create([
                'user_id' => $user_id,
                'action_id' => $action,
                'description' => $description,
                'date' => Carbon::now(),
            ]);

            if ($action == 11 || $action == 12) {
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
