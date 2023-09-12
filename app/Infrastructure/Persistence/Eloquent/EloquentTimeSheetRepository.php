<?php

namespace App\Infrastructure\Persistence\Eloquent;

use App\Domain\Models\CD\Appointment;
use App\Domain\Models\CD\AppointmentStatus;
use App\Domain\Models\CD\Consultant;
use App\Domain\Models\CD\Customer;
use App\Domain\Models\CD\Shift;
use App\Domain\Models\CD\WorkDay;
use App\Domain\Models\Employee;
use App\Domain\Repositories\TimeSheetRepositoryInterface;
use App\Exceptions\EntryNotFoundException;
use App\Notifications\ReservationCancelledByConsultant;
use App\Notifications\ReservationCancelledByCustomer;
use App\Notifications\ReservationCancelledByEmployee;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Throwable;

class EloquentTimeSheetRepository implements TimeSheetRepositoryInterface
{
    public function getTimeSheetList(): Collection
    {
        $time_sheet = Shift::query()/*->with('intervals')*/
        ;

        // check if the request has search by employee name
        if (request()->has('name')) {
            $name = request()->query('name');

            // trim the name
            $name = trim($name);

            // make the name lower case
            $name = strtolower($name);

            $time_sheet->whereRaw('LOWER(name) LIKE ?', ["%$name%"]);
        }

        return $time_sheet->get();

    }


    private function getIntervalIdIfExists(mixed $startTime, mixed $endTime)
    {
        $interval = DB::table('intervals')
            ->where('start_time', $startTime)
            ->where('end_time', $endTime)
            ->first();

        return $interval?->id;
    }

    /**
     * @throws Throwable
     */
    public function createTimeSheet(array $data): Shift|Builder|null
    {
        try {
            DB::beginTransaction();

            $user_id = Auth::id();
            $consultant = Consultant::query()->where('user_id', '=', $user_id)->first();

            $shift = Shift::query()->create([
                'consultant_id' => $consultant->id,
                'name' => $data['name'],
            ]);

            foreach ($data['periods'] as $period) {
                $start_time = $period['start_time'];
                $end_time = $period['end_time'];

                $intervalId = $this->getIntervalIdIfExists($start_time, $end_time);

                // If the interval doesn't exist, create a new record and get its ID
                if (!$intervalId) {
                    $intervalId = DB::table('intervals')->insertGetId([
                        'start_time' => $start_time,
                        'end_time' => $end_time,
                    ]);
                }

                $shift->intervals()->attach($intervalId);
            }

            DB::commit();
            return $shift;
        } catch (Exception $exception) {
            DB::rollBack();
            throw $exception;
        }
    }


    /**
     * @throws Throwable
     */
    public function deleteTimeSheet($id): Shift|Builder|null
    {
        try {
            DB::beginTransaction();

            $shift = Shift::query()->findOrFail($id);

            $shift->intervals()->detach();
            $shift->delete();

            DB::commit();
            return $shift;
        } catch (Exception) {
            DB::rollBack();
            throw new EntryNotFoundException("Entry with ID $id not found.");
        }
    }

    /**
     * @throws EntryNotFoundException
     * @throws Throwable
     */
    public function addWorkDay(array $data): Collection
    {
        try {

            $createdAppointments = new Collection();
            $user_id = Auth::id();
            $consultant = Consultant::query()->where('user_id', '=', $user_id)->first();

            DB::beginTransaction();
            foreach ($data['dates'] as $date) {

                foreach ($consultant->shifts()->get() as $shift) {
                    $records = $shift->workDays()
                        ->where('day_date', '=', $date['date'])
                        ->whereHas('appointments', function ($query) {
                            $query->whereNotIn('status_id', [1, 2, 3]);
                        })->get();

                    if ($records->count() > 0) {
                        abort(400, "يوجد مواعيد غير ملغية مرتبطة بهذا اليوم");
                    }
                }

                $work_day = WorkDay::query()->create([
                    'shift_id' => $date['shift_Id'],
                    'day_date' => $date['date'],
                ]);


                $shift = Shift::query()->find($date['shift_Id'])->first();


                // get the intervals associated with the shift and insert them as appointments
                foreach ($shift->intervals as $interval) {
                    $start_time = $interval->start_time;
                    $end_time = $interval->end_time;

                    $appointment = Appointment::query()->create([
                        'work_day_id' => $work_day->id,
                        'start_time' => $start_time,
                        'end_time' => $end_time,
                        'customer_id' => null,
                        'status_id' => AppointmentStatus::STATUS_AVAILABLE,
                        'cancellation_reason' => null,
                    ]);

                    $createdAppointments->push($appointment);
                }
            }
            DB::commit();

            return $createdAppointments;
        } catch (Exception $exception) {
            DB::rollBack();
            throw $exception;
        }
    }

    /**
     * @throws EntryNotFoundException
     */
    public function bookAnAppointmentByEmployee(int $appointment_id, int $customer_id): Appointment|Builder|null
    {
        try {
            $appointment = Appointment::query()->findOrFail($appointment_id);

            // test if the date is not expired
            //TODO : today considered in the past
            $work_day = WorkDay::query()->find($appointment->work_day_id);
            $targetDate = Carbon::parse($work_day->day_date);
            if ($targetDate->isPast()) {
                throw new EntryNotFoundException("Can't update the appointment , the date is expired");
            }

            //test if the customer is not verified or blocked
            $customer = Customer::query()->findOrFail($customer_id);
            if ($customer['blocked'] == 1 || $customer['verified'] == 0) {
                throw new EntryNotFoundException("Can't update the appointment , the customer is blocked or unverified");
            }

        } catch (Exception $exception) {
//            throw new EntryNotFoundException("Appointment with ID $appointment_id not found.");
            throw $exception;
        }

        $appointment->customer_id = $customer_id;
        $appointment->status_id = AppointmentStatus::STATUS_RESERVED;
        $appointment->save();

        return $appointment;
    }


    public function getConsultantSchedule(): Collection
    {
        $user_id = Auth::id();
        $consultant = Consultant::query()->where('user_id', '=', $user_id)->first();

        $shift = Shift::query()->where('consultant_id', '=', $consultant->id)->pluck('id');

        $work_days = WorkDay::query()->whereIn('shift_id', $shift);

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
    }


    /**
     * @throws EntryNotFoundException
     */
    public function cancelAppointmentByConsultant($id): Appointment|Builder|null
    {
        try {
            $canceled_appointment = Appointment::query()->findOrFail($id);

            $canceled_appointment->status_id = AppointmentStatus::STATUS_CANCELED_BY_CONSULTANT;
            $canceled_appointment->save();

            return $canceled_appointment;
        } catch (Exception) {
            throw new EntryNotFoundException("Appointment with ID $id not found.");
        }
    }

    public function getCanceledAppointment(): LengthAwarePaginator
    {
        $consultant_id = request('consultant_id');
        $start_date = request('start_date');
        $end_date = request('end_date');


        //filter by consultant id
        $shift = Shift::query();
        if ($consultant_id) {
            $shift->where('consultant_id', '=', $consultant_id);
        }
        $shift = $shift->pluck('id');

        //filter by start & end date
        $work_days = WorkDay::query()->whereIn('shift_id', $shift);
        if ($start_date) {
            $work_days->where('day_date', '>=', $start_date)->pluck('id');
        }
        if ($end_date) {
            $work_days->where('day_date', '<=', $end_date)->pluck('id');
        }
        $work_days = $work_days->pluck('id');

        return Appointment::query()
            ->whereIn('work_day_id', $work_days)
            ->where('status_id', '=',
                AppointmentStatus::STATUS_CANCELED_BY_CONSULTANT
                || AppointmentStatus::STATUS_CANCELED_BY_EMPLOYEE)
            ->paginate(10);
    }

    public
    function cancelReservationByCustomer($appointment): Appointment|Builder|null
    {
        $appointment->update([
            'status_id' => AppointmentStatus::STATUS_CANCELED_BY_CUSTOMER,
        ]);
//
//        // Notify the consultant
//        $consultant = $appointment->getConsultant();
//        $customer = $appointment->customer;
//        $customerId = $customer->id;
//        $customerName = $customer->getFullNameAttribute();
//        $consultant?->notify
//        (new ReservationCancelledByCustomer($customerId, $customerName, $appointment->workDay->day_date));

        return $appointment;
    }

    public
    function cancelReservationByEmployee($appointment): Appointment|Builder|null
    {
        // set the status to canceled by employee
        $appointment->update([
            'status_id' => AppointmentStatus::STATUS_CANCELED_BY_EMPLOYEE,
        ]);

//        $user_id = Auth::id();
//        $employee = Employee::query()->where('user_id', '=', $user_id)->first();
//
//        // Notify the customer & consultant
//        $employeeId = $employee->id; // TODO : check this
//        $employee = Employee::query()->find($employeeId);
//        $employeeName = $employee->getFullNameAttribute();
//
//        // Notify the consultant
//        $consultant = $appointment->getConsultant();
//        $customer = $appointment->customer()->first();
//        $consultant?->notify
//        (new ReservationCancelledByEmployee($employeeId, $employeeName, $appointment->workDay->day_date,
//            1, $customer->getFullNameAttribute()));
//
//        // Notify the customer
//        $customer?->notify
//        (new ReservationCancelledByEmployee($employeeId, $employeeName, $appointment->workDay->day_date, 2));

        return $appointment;
    }

    public
    function cancelReservationByConsultant($appointment): Appointment|Builder|null
    {
        // set the status to canceled by consultant
        $appointment->update([
            'status_id' => AppointmentStatus::STATUS_CANCELED_BY_CONSULTANT
        ]);
//        $user_id = Auth::id();
//        $consultant = Consultant::query()->where('user_id', '=', $user_id)->first();
//
//        $consultantId = $consultant->id; // TODO : check this
//        $consultant = Consultant::query()->find($consultantId);
//        $consultantName = $consultant->getFullNameAttribute();
//
//        // Notify the customer
//        $customer = $appointment->customer()->first();
//        $customer?->notify
//        (new ReservationCancelledByConsultant($consultantId, $consultantName, $appointment->workDay->day_date));

        return $appointment;
    }

    public
    function cancelReservation(Appointment $appointment): Appointment|Builder|null
    {
        // set the status to canceled by consultant
        $appointment->update([
            'status_id' => AppointmentStatus::STATUS_AVAILABLE,
            'customer_id' => null,
        ]);

        return $appointment;
    }

    /**
     * @throws EntryNotFoundException
     */
    public
    function getConsultantTimeSlots($consultant_id, $date): Collection
    {

        try {

            $consultant = Consultant::query()->findOrFail($consultant_id);
        } catch (Exception) {
            throw new EntryNotFoundException("المستشار غير موجود.");
        }

        $shift = Shift::query()->where('consultant_id', '=', $consultant->id)->pluck('id');

        $work_days = WorkDay::query()->whereIn('shift_id', $shift)->whereDate('day_date', '=', $date)->pluck('id');

        return Appointment::query()->whereIn('work_day_id', $work_days)->get();
    }
}
