<?php

namespace App\Domain\Services;

use App\Domain\Repositories\EmployeeRepositoryInterface;
use App\Domain\Models\Employee;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use App\Infrastructure\Persistence\Eloquent\EloquentFingerDeviceRepository;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class EmployeeService
{
    private EmployeeRepositoryInterface $employeeRepository;

    public function __construct(EmployeeRepositoryInterface $employeeRepository)
    {
        $this->employeeRepository = $employeeRepository;
    }

    public function getEmployeeList(): LengthAwarePaginator
    {
        return $this->employeeRepository->getEmployeeList();
    }

    public function getAllEmployees(): LengthAwarePaginator
    {
        return $this->employeeRepository->getAllEmployees();
    }

    public function getJobTitlesHistory(int $id): Collection
    {
        return $this->employeeRepository->getJobTitlesHistory($id);
    }

    public function getDepartmentsHistory(int $id): Collection
    {
        return $this->employeeRepository->getDepartmentsHistory($id);
    }

    public function getEmployeeAbsenceHistory(int $id): array
    {
        return $this->employeeRepository->getEmployeeAbsenceHistory($id);
    }

    public function getEmployeeListByDepId($dep_id): array
    {
        return $this->employeeRepository->getEmployeeListByDepId($dep_id);
    }

    public function getEmployeeListByTitleId($title_id): array
    {
        return $this->employeeRepository->getEmployeeListByTitleId($title_id);
    }

    public function getEmployeeById(int $id): Builder|Model
    {
        return $this->employeeRepository->getEmployeeById($id);
    }

    public function createEmployee(array $data): Builder|Model
    {
        $employee = $this->employeeRepository->createEmployee($data);

        // TODO: REACTIVATE THIS CODE
        // Add employee to the finger device
//        $fingerDeviceService = new FingerDeviceService(new EloquentFingerDeviceRepository());
//        $fingerDeviceService->addEmployeeToFingerDevice($employee['emp_id']);

        return $employee;
    }

    public function deleteEmployee($id): Builder|Model|null
    {
        $employee = $this->employeeRepository->deleteEmployee($id);

        // Delete employee from finger device
        if (!$employee) {
            $fingerDeviceService = new FingerDeviceService(new EloquentFingerDeviceRepository());
            $fingerDeviceService->deleteEmployeeFromFingerDevice($id);
        }

        return $employee;
    }

    public function editEmployeeCredentials(int $id, array $data): Employee|Builder|null
    {
        return $this->employeeRepository->editEmployeeCredentials($id, $data);
    }

    public function editEmployeeDepartment(int $id, array $data): Employee|Builder|null
    {
        return $this->employeeRepository->editEmployeeDepartment($id, $data);
    }

    public function editEmployeeEmploymentStatus(int $id, array $data): Employee|Builder|null
    {
        return $this->employeeRepository->editEmployeeEmploymentStatus($id, $data);
    }

    public function editEmployeeSchedule(int $id, array $data): Employee|Builder|null
    {
        return $this->employeeRepository->editEmployeeSchedule($id, $data);
    }

    public function editEmployeePermissions(int $id, array $data): Employee|Builder|null
    {
        return $this->employeeRepository->editEmployeePermissions($id, $data);
    }
}
