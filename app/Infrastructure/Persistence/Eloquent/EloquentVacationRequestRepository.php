<?php

namespace App\Infrastructure\Persistence\Eloquent;

use App\Domain\Models\Employee;
use App\Domain\Models\VacationRequest;
use App\Domain\Repositories\VacationRequestRepositoryInterface;
use App\Exceptions\EntryNotFoundException;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class EloquentVacationRequestRepository implements VacationRequestRepositoryInterface
{
    public function getVacationRequestList(): LengthAwarePaginator
    {
        $vacation_requests = VacationRequest::query();

        $req_stat = request('req_stat');
        $start_date = request('start_date');

        // get list of comma separated req_stat ids
        if ($req_stat != null) {

            // convert the comma separated string to an array
            $req_stat_ids = explode(',', $req_stat);

            // convert the array of strings to an array of integers
            $req_stat_ids = array_map('intval', $req_stat_ids);

            // filter the vacation requests by the req_stat ids
            $vacation_requests->whereIn('req_stat', $req_stat_ids);
        }

        if ($start_date != null) {
            $vacation_requests->whereDate('start_date', '=', $start_date);
        }

        return $vacation_requests->paginate(10);
    }

    /**
     * @throws EntryNotFoundException
     */
    public function getVacationRequestById(int $id): VacationRequest|Builder
    {
        try {
            return VacationRequest::query()->findOrFail($id);
        } catch (Exception) {
            throw new EntryNotFoundException("Entry with ID $id not found.");
        }
    }

    /**
     * @throws Exception
     */
    public function createVacationRequest(array $data): VacationRequest|Builder
    {

        // TODO : RE ENABLE THIS
//        $user = Auth::user();
//        $emp_id = $user->emp_id;

        // get the user id from data
        $user_id = $data['user_id'];

        try {

            // get the employee id from the user id
            $emp_id = Employee::query()
                ->where('user_id', '=', $user_id)
                ->firstOrFail()
                ->emp_id;

            return VacationRequest::query()->create([
                "emp_id" => $emp_id,
                "req_stat" => 1,
                "description" => $data["description"],
                "start_date" => $data["start_date"],
                "duration" => $data["duration"],
            ]);
        } catch (Exception) {
            throw new EntryNotFoundException("User with ID $user_id not found.");
        }
    }

    /**
     * @throws EntryNotFoundException
     */
    public function updateVacationRequest(int $id, array $data): VacationRequest|Builder
    {
        try {
            $vacationRequest = VacationRequest::query()->findOrFail($id);
        } catch (Exception $exception) {
            throw new EntryNotFoundException("Entry with ID $id not found.");
        }

        $vacationRequest->description = $data["description"] ?? $vacationRequest->description;
        $vacationRequest->start_date = $data["start_date"] ?? $vacationRequest->start_date;
        $vacationRequest->duration = $data["duration"] ?? $vacationRequest->duration;
        $vacationRequest->save();

        return $vacationRequest;
    }

    /**
     * @throws EntryNotFoundException
     */
    public function deleteVacationRequest($id): VacationRequest|Builder|null
    {
        try {
            $vacationRequest = VacationRequest::query()
                ->findOrFail($id);

        } catch (Exception) {
            throw new EntryNotFoundException("Entry with ID $id not found.");
        }
        // delete the vacation request
        $vacationRequest->delete();

        // return the deleted vacation request
        return $vacationRequest;
    }

    /**
     * @throws EntryNotFoundException
     * @throws Exception
     */
    public function acceptVacationRequest($id): VacationRequest|Builder
    {
        try {
            $vacationRequest = VacationRequest::query()
                ->where('req_stat', '=', 1)
                ->findOrFail($id);

        } catch (Exception) {
            throw new EntryNotFoundException("Vacation Request with ID $id not found.");
        }

        try {
            DB::beginTransaction();

            // set the request status to accepted
            $vacationRequest->update([
                "req_stat" => 2
            ]);

            // add a new vacation to the employee
            $vacationRequest->employee->vacations()->create([
                'start_date' => $vacationRequest->start_date,
                'total_days' => $vacationRequest->duration,
                'remaining_days' => $vacationRequest->duration,
            ]);

            DB::commit();
        } catch (Exception $exception) {
            DB::rollBack();
            throw $exception;
        }

        // return the updated vacation request
        return $vacationRequest;
    }

    /**
     * @throws EntryNotFoundException
     */
    public function rejectVacationRequest($id): VacationRequest|Builder
    {
        try {

            // get the vacation request with the given id
            $vacationRequest = VacationRequest::query()
                ->where('req_stat', '=', 1)
                ->findOrFail($id);

        } catch (Exception $exception) {
            throw new EntryNotFoundException("Entry with ID $id not found.");
        }

        // set the request status to rejected
        $vacationRequest->update([
            "req_stat" => 3
        ]);

        // return the updated vacation request
        return $vacationRequest;
    }
}
