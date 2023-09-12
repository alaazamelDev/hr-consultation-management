<?php

namespace App\Domain\Services;

use App\Domain\Models\CD\CaseNote;
use App\Domain\Repositories\AppointmentRepositoryInterface;
use App\Domain\Models\CD\Appointment;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class AppointmentService
{
    private AppointmentRepositoryInterface $AppointmentRepository;

    public function __construct(AppointmentRepositoryInterface $AppointmentRepository)
    {
        $this->AppointmentRepository = $AppointmentRepository;
    }

    public function getAppointmentList(): Collection
    {
        return $this->AppointmentRepository->getAppointmentList();
    }

    public function getAppointmentById(int $id): Appointment|Builder|null
    {
        return $this->AppointmentRepository->getAppointmentById($id);
    }

    public function createAppointment(array $data): Appointment|Builder|null
    {
        return $this->AppointmentRepository->createAppointment($data);
    }

    public function updateAppointment(int $id, array $data): Appointment|Builder|null
    {
        return $this->AppointmentRepository->updateAppointment($id, $data);
    }

    public function deleteAppointment($id): Appointment|Builder|null
    {
        return $this->AppointmentRepository->deleteAppointment($id);
    }

    public function attendanceModification($app_id, $status_id): Appointment|Builder|null
    {
        return $this->AppointmentRepository->attendanceModification($app_id, $status_id);
    }

    public function appointmentPreview(array $data): CaseNote|Builder|null
    {
        return $this->AppointmentRepository->appointmentPreview($data);
    }


}
