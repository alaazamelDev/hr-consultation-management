<?php

namespace App\Domain\Services;

use App\Domain\Repositories\AbsenceRepositoryInterface;
use App\Domain\Models\Absence;
use App\Infrastructure\Persistence\Eloquent\EloquentAttendanceRepository;
use App\Infrastructure\Persistence\Eloquent\EloquentEmployeeRepository;
use App\Infrastructure\Persistence\Eloquent\EloquentHolidayRepository;
use App\Infrastructure\Persistence\Eloquent\EloquentWorkingDayRepository;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class AbsenceService
{
    private AbsenceRepositoryInterface $AbsenceRepository;

    public function __construct(AbsenceRepositoryInterface $AbsenceRepository)
    {
        $this->AbsenceRepository = $AbsenceRepository;
    }

    public function getAbsenceList(): Collection
    {
        return $this->AbsenceRepository->getAbsenceList();
    }

    public function getAbsenceById(int $id): Absence|Builder|null
    {
        return $this->AbsenceRepository->getAbsenceById($id);
    }

    public function createAbsence(array $data): Absence|Builder|null
    {
        return $this->AbsenceRepository->createAbsence($data);
    }

    public function updateAbsenceStatus($id,$status): Absence|Builder|null
    {
        return $this->AbsenceRepository->updateAbsenceStatus($id,$status);
    }

    public function getEmployeeAbsences($id): Collection|null
    {
        return $this->AbsenceRepository->getEmployeeAbsences($id);
    }

    public function storeAbsenceForEmployees($date): array|null
    {
        $attendanceService = new AttendanceService(new EloquentAttendanceRepository());

        // Get all employees who attended in that day
        $all_att_employees = $attendanceService->getAllEmployeesAttByDate($date)->pluck('employee.emp_id');

        // Get all employees in the system
        $employeeService = new EmployeeService(new EloquentEmployeeRepository());
        $all_employees = $employeeService->getEmployeeList()->getCollection()->pluck('emp_id');

        $absent_employees = $all_employees->diff($all_att_employees)->values();

        $ids =[];
        foreach ($absent_employees as $absent_employee) {
            $absence = $this->createAbsence([
                'emp_id' => $absent_employee,
                'absence_date' => $date
            ]);

            if(!$absence['message']){
                $ids [] = $absent_employee;
            }
        }

        return $ids;
    }
}
