<?php

namespace App\Infrastructure\Persistence\Eloquent;

use App\Domain\Models\CD\AppointmentStatus;
use App\Domain\Models\CD\CaseNote;
use App\Domain\Models\CD\UnRegisteredAccount;
use App\Domain\Models\CD\WorkDay;
use App\Domain\Repositories\AppointmentRepositoryInterface;
use App\Domain\Models\CD\Appointment;
use Carbon\Carbon;
use Exception;
use Illuminate\Container\EntryNotFoundException;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use phpDocumentor\Reflection\PseudoTypes\NonEmptyLowercaseString;

class EloquentAppointmentRepository implements AppointmentRepositoryInterface
{
    public function getAppointmentList(): Collection
    {

        $work_days = WorkDay::query();

        $start_date = request('start_date');
        $end_date = request('end_date');

        if ($start_date) {
            $work_days->where('day_date', '>=', $start_date)->pluck('id');
        }
        if ($end_date) {
            $work_days->where('day_date', '<=', $end_date)->pluck('id');
        }
        $work_days = $work_days->pluck('id');

        return Appointment::query()->whereIn('work_day_id', $work_days)->get();
//        $appointment = Appointment::query()->whereIn('work_day_id', $work_days)->first();
//        dd($appointment->unRegisteredAccount);
//
//        $app = Appointment::query()->find(4);
//
////        $unregistered = UnRegisteredAccount::query()->first();
//        dd($app->unRegisteredAccount);
    }


    /**
     * @throws EntryNotFoundException
     */
    public function getAppointmentById(int $id): Appointment|Builder|null
    {
        try {
            $appointment = Appointment::query()
                ->where('id', '=', $id)
                ->firstOrFail();
        } catch (Exception $e) {
            throw new EntryNotFoundException("appointment with id $id not found");
        }
        return $appointment;
    }

    public function createAppointment(array $data): Appointment|Builder|null
    {
        // TODO: Implement the logic to create a Appointment
    }

    public function updateAppointment(int $id, array $data): Appointment|Builder|null
    {
        // TODO: Implement the logic to update a Appointment
    }

    public function deleteAppointment($id): Appointment|Builder|null
    {
        // TODO: Implement the logic to delete a Appointment
    }

    /**
     * @throws Exception
     */
    public function attendanceModification($app_id, $status_id): Appointment|Builder|null
    {
        try {
            $appointment = Appointment::query()->find($app_id);

            if (!$appointment)
                abort(403, "Appointment with ID $app_id not found.");

            //status_id must be 4 or 7 or 8
            // Check if status_id is within the specified range
            $validStatuses = [
                AppointmentStatus::STATUS_COMPLETED
                , AppointmentStatus::STATUS_MISSED_BY_CUSTOMER
                , AppointmentStatus::STATUS_MISSED_BY_CONSULTANT
            ];


            if (in_array($status_id, $validStatuses)) {
                $appointment->status_id = $status_id;
                $appointment->save();
            } else if (($status_id == AppointmentStatus::STATUS_RESERVED) && $appointment->is_future) {
                $appointment->status_id = $status_id;
                $appointment->save();
            } else if (($status_id == AppointmentStatus::STATUS_RESERVED_ON_PHONE) && $appointment->is_future) {
                $appointment->status_id = $status_id;
                $appointment->load('unRegisteredAccount');
                $appointment->save();
            } else if ($status_id == AppointmentStatus::STATUS_ATTENDANCE_IS_NOT_RECORDED && $appointment->is_past) {
                $appointment->status_id = $status_id;
                $appointment->save();
            } else {
                abort(400, "Invalid status id");
            }

            return $appointment;
        } catch (Exception $exception) {
            throw new EntryNotFoundException("Appointment with ID $app_id not found.");

        }

    }

    public function appointmentPreview(array $data): CaseNote|Builder|null
    {
        return CaseNote::query()->create([
            'app_id' => $data['app_id'],
            'title' => $data['title'],
            'description' => $data['description'],
        ]);
    }

}
