<?php

namespace App\Domain\Services;

use App\Domain\Repositories\AttendanceRepositoryInterface;
use App\Domain\Models\Attendance;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class AttendanceService
{
    private AttendanceRepositoryInterface $AttendanceRepository;

    public function __construct(AttendanceRepositoryInterface $AttendanceRepository)
    {
        $this->AttendanceRepository = $AttendanceRepository;
    }

    public function getAttendanceList(): array
    {
        return $this->AttendanceRepository->getAttendanceList();
    }

    public function getAttendanceById(int $id):Attendance|Builder|null
    {
        return $this->AttendanceRepository->getAttendanceById($id);
    }

    public function getAttendanceByEmpId(int $emp_id):array|null
    {
        return $this->AttendanceRepository->getAttendanceByEmpId($emp_id);
    }

    public function createAttendance(array $data): Attendance|Builder|null
    {
        return $this->AttendanceRepository->createAttendance($data);
    }

    public function updateAttendance(int $id, array $data): Attendance|Builder|null
    {
        return $this->AttendanceRepository->updateAttendance($id, $data);
    }

    public function deleteAttendance($id): Attendance|Builder|null
    {
        return $this->AttendanceRepository->deleteAttendance($id);
    }

    public function getEmployeeAttByDate($emp_id,$date): Attendance|Builder|null
    {
        return $this->AttendanceRepository->getEmployeeAttByDate($emp_id,$date);
    }

    public function getAllEmployeesAttByDate($date): Collection|null
    {
        return $this->AttendanceRepository->getAllEmployeesAttByDate($date);
    }

}
