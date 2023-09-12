<?php

namespace App\Observers;


use App\Domain\Models\AffectedUser;
use App\Domain\Models\Employee;
use App\Domain\Models\Log;
use App\Domain\Models\User;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class EmployeeObserver
{
    /**
     * Handle the Employee "created" event.
     */
    public function created(Employee $employee): void
    {
        //user
        $user_id = Auth::id();
        $user_name = User::query()->select('username')->find($user_id);

        //affected user
        $full_name = $employee->full_name;
        $affected_user_id = $employee->user->user_id;

        $this->logAction($user_id, 5,
            "Employee (Name: $user_name ) & (ID: $user_id ) Added a  new Employee (Name : $full_name) & (ID : $affected_user_id)."
            , $affected_user_id);
    }

    /**
     * Handle the Employee "updated" event.
     */
    public
    function updated(Employee $employee): void
    {
        //user
        $user_id = Auth::id();
        $user_name = User::query()->select('username')->find($user_id);

        //affected user
        $full_name = $employee->full_name;
        $affected_user_id = $employee->user->user_id;

        $updatedFields = $employee->getDirty();

        if (!empty($updatedFields)) {

            $this->logAction($user_id, 6,
                "Employee (Name: $user_name ) & (ID: $user_id ) Updated a specific field for Employee (Name : $full_name) & (ID : $affected_user_id)."
                , $affected_user_id);
        }
    }


    /**
     * Handle the Employee "deleted" event.
     */
    public
    function deleted(Employee $employee): void
    {
        //user
        $user_id = Auth::id();
        $user_name = User::query()->select('username')->find($user_id);

        //affected user
        $full_name = $employee->full_name;
        $affected_user_id = $employee->user->user_id;


        $this->logAction($user_id, 7,
            "Employee (Name: $user_name ) & (ID: $user_id ) Deleted (Name : $full_name) & (ID : $affected_user_id).",
            $affected_user_id);
    }


    private
    function logAction(int $user_id, int $action, string $description, int $affected_user_id): void
    {
        try {
            // start transaction
            DB::beginTransaction();
            $logData = [
                'user_id' => $user_id,
                'action_id' => $action,
                'description' => $description,
                'date' => now(),
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
