<?php

namespace App\Observers;


use App\Domain\Models\Absence;
use App\Domain\Models\AffectedUser;
use App\Domain\Models\Log;
use App\Domain\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AbsenceObserver
{
    /**
     * Handle the Absence "updated" event.
     */
    public function updated(Absence $absence): void
    {

        $user_id = Auth::id();
        $user_name = User::query()->select('username')->find($user_id);

        $full_name = null;
        $user_id = (int)null;
        $employee = $absence->employee;
        if (isset($employee)) {
            $full_name = $employee->full_name;
            $user_id = $employee->user->user_id;
            $affected_user_id = $employee->user->user_id;
        }

        $updatedFields = $absence->getDirty();

        if (array_key_exists('absence_status_id', $updatedFields)) {
            if ($updatedFields['absence_status_id'] == 1) {
                $this->logAction($user_id,
                    "Employee (Name: $user_name ) & (ID: $user_id ) Justified the absence for the Employee (Name: $full_name) & (ID: $affected_user_id )."
                    , $affected_user_id);
            }

        }
    }

    private function logAction(int $user_id, string $description, int $affected_user_id): void
    {
        try {
            // start transaction
            DB::beginTransaction();
            $logData = [
                'user_id' => $user_id,
                'action_id' => 26,
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
