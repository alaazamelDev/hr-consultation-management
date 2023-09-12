<?php

namespace App\Domain\Services;

use App\Domain\Models\CD\Appointment;
use App\Domain\Models\CD\Shift;
use App\Domain\Repositories\TimeSheetRepositoryInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class TimeSheetService
{
    private TimeSheetRepositoryInterface $TimeSheetRepository;

    public function __construct(TimeSheetRepositoryInterface $TimeSheetRepository)
    {
        $this->TimeSheetRepository = $TimeSheetRepository;
    }

    public function getTimeSheetList(): Collection
    {
        return $this->TimeSheetRepository->getTimeSheetList();
    }

    public function createTimeSheet(array $data): Shift|Builder|null
    {
        return $this->TimeSheetRepository->createTimeSheet($data);
    }

    public function deleteTimeSheet($id): Shift|Builder|null
    {
        return $this->TimeSheetRepository->deleteTimeSheet($id);
    }

    public function addWorkDay(array $data): Collection
    {
        return $this->TimeSheetRepository->addWorkDay($data);
    }

    public function bookAnAppointmentByEmployee(int $appointment_id, int $customer_id): Appointment|Builder|null
    {
        return $this->TimeSheetRepository->bookAnAppointmentByEmployee($appointment_id, $customer_id);
    }

    public function getConsultantSchedule(): Collection
    {
        return $this->TimeSheetRepository->getConsultantSchedule();
    }

    public function cancelAppointmentByConsultant($id): Appointment|Builder|null
    {
        return $this->TimeSheetRepository->cancelAppointmentByConsultant($id);
    }

    public function getCanceledAppointment(): LengthAwarePaginator
    {
        return $this->TimeSheetRepository->getCanceledAppointment();
    }

    public function cancelReservationByCustomer(Appointment $appointment): Appointment|Builder|null
    {
        return $this->TimeSheetRepository->cancelReservationByCustomer($appointment);
    }

    public function cancelReservationByEmployee(Appointment $appointment): Appointment|Builder|null
    {
        return $this->TimeSheetRepository->cancelReservationByEmployee($appointment);
    }

    public function cancelReservationByConsultant(Appointment $appointment): Appointment|Builder|null
    {
        return $this->TimeSheetRepository->cancelReservationByConsultant($appointment);
    }

    public function cancelReservation(Appointment $appointment): Appointment|Builder|null
    {
        return $this->TimeSheetRepository->cancelReservation($appointment);
    }

    public function getConsultantTimeSlots($consultant_id, $date): Collection
    {
        return $this->TimeSheetRepository->getConsultantTimeSlots($consultant_id, $date);
    }
}
