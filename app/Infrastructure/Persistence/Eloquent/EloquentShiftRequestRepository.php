<?php

namespace App\Infrastructure\Persistence\Eloquent;

use App\Domain\Models\Employee;
use App\Domain\Repositories\ShiftRequestRepositoryInterface;
use App\Domain\Models\ShiftRequest;
use App\Exceptions\EntryNotFoundException;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use \Illuminate\Pagination\LengthAwarePaginator;


class EloquentShiftRequestRepository implements ShiftRequestRepositoryInterface
{
    public function getShiftRequestList(): LengthAwarePaginator
    {
        $shift_requests = ShiftRequest::query();

        $req_stat = request('req_stat');

        // query parameters for shift with date range
        if ($req_stat) {
            $shift_requests->where('req_stat', '=', $req_stat);
        }

        return $shift_requests->with(['employee'])->paginate(10);

    }

    /**
     * @throws EntryNotFoundException
     */
    public function getShiftRequestById(int $id): Builder|Model|null
    {
        try {

            $shift_request = ShiftRequest::query()
                ->with(['employee'])
                ->findOrFail($id)
                ->first();
        } catch (Exception) {
            throw new EntryNotFoundException("Shift Request with ID $id not found.");
        }
        return $shift_request;
    }

    /**
     * @throws Exception
     */
    public function createShiftRequest(array $data): ShiftRequest|Builder|null
    {
        // get the user id from data
        $user_id = $data['user_id'];

        try {

            // get the employee id from the user id
            $emp_id = Employee::query()
                ->where('user_id', '=', $user_id)
                ->firstOrFail()
                ->emp_id;

        } catch (Exception $exception) {
            throw new EntryNotFoundException("User with ID $user_id not found.");
        }

        return ShiftRequest::query()->create([
            "emp_id" => $emp_id,
            'req_stat' => 1, // default status is 'pending
            "description" => $data["description"],
            "new_time_in" => $data["new_time_in"],
            "new_time_out" => $data["new_time_out"],
            "start_date" => $data["start_date"],
            "duration" => $data["duration"],
            "remaining_days" => optional($data)["remaining_days"] ?? $data["duration"],
        ]);
    }

    /**
     * @throws EntryNotFoundException
     */
    public
    function updateShiftRequest(int $id, array $data): ShiftRequest|Builder|null
    {
        try {
            $shiftRequest = ShiftRequest::query()->findOrFail($id);
        } catch (Exception) {
            throw new EntryNotFoundException("Shift Request with ID $id not found.");
        }


        $shiftRequest->description = $data['description'] ?? $shiftRequest->description;
        $shiftRequest->new_time_in = $data['new_time_in'] ?? $shiftRequest->new_time_in;
        $shiftRequest->new_time_out = $data['new_time_out'] ?? $shiftRequest->new_time_out;
        $shiftRequest->start_date = $data['start_date'] ?? $shiftRequest->start_date;
        $shiftRequest->duration = $data['duration'] ?? $shiftRequest->duration;
        $shiftRequest->remaining_days = $data['remaining_days'] ?? $shiftRequest->remaining_days;

        $shiftRequest->save();

        return $shiftRequest;
    }


    /**
     * @throws EntryNotFoundException
     */
    public function deleteShiftRequest($id): ShiftRequest|Builder|null
    {
        try {
            $shiftRequest = ShiftRequest::query()
                ->findOrFail($id);
        } catch (Exception) {
            throw new EntryNotFoundException("Entry with ID $id not found.");
        }

        $shiftRequest->delete();
        return $shiftRequest;

    }

    //TODO : handle where req_id != 1

    /**
     * @throws EntryNotFoundException
     */
    public function acceptShiftRequest($id): ShiftRequest|Builder|null
    {
        try {
            $shiftRequest = ShiftRequest::query()
                ->where("req_stat", '=', 1)
                ->findOrFail($id);

        } catch (Exception) {
            throw new EntryNotFoundException("Shift Request with ID $id not found.");
        }

        // update the shift request status to accepted
        $shiftRequest->req_stat = 2;

        // save the changes
        $shiftRequest->save();

        return $shiftRequest;

    }

    /**
     * @throws EntryNotFoundException
     */
    public function rejectShiftRequest($id): Builder|ShiftRequest
    {
        try {
            $shiftRequest = ShiftRequest::query()
                ->where("req_stat", '=', 1)
                ->findOrFail($id);

            $shiftRequest->req_stat = 3;

            $shiftRequest->save();

            return $shiftRequest;
        } catch (Exception) {
            throw new EntryNotFoundException("Entry with ID $id not found.");
        }

    }

}
