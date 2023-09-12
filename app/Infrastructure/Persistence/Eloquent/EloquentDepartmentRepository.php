<?php

namespace App\Infrastructure\Persistence\Eloquent;

use App\Domain\Models\Department;
use App\Domain\Repositories\DepartmentRepositoryInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class EloquentDepartmentRepository implements DepartmentRepositoryInterface
{
    public function getList(): Collection
    {
        $departments = Department::query();

        if (request()->has('name')) {
            $name = request()->query('name');

            $name = trim($name);

            $name = strtolower($name);

            $departments->whereRaw('LOWER(name) LIKE ?', ["%$name%"]);

        }

        $departments = $departments->get();

        $employeeRepository = new EloquentEmployeeRepository();
        foreach ($departments as &$department) {
            $department['employees_count'] = count($employeeRepository->getEmployeeListByDepId($department['dep_id']));
        }
        return $departments;
    }

    public function getById(int $id): ?Department
    {
        $employeeRepository = new EloquentEmployeeRepository();
        $department = Department::find($id);
        if ($department) $department['employees_count'] = count($employeeRepository->getEmployeeListByDepId($department['dep_id']));
        return $department;
    }

    public function create(array $data): Model
    {
        return Department::query()->create([
            'name' => $data['name'],
            'description' => $data['description'],
        ]);
    }

    public function update(int $id, array $data): Model
    {
        $department = Department::query()->find($id);
        $department->name = $data['name'] ?? $department->name;
        $department->description = $data['description'] ?? $department->description;
        $department->save();
        return $department;
    }

    public function delete($id): Department
    {
        $employeeRepository = new EloquentEmployeeRepository();
        $department = Department::find($id);
        $department['employees_count'] = count($employeeRepository->getEmployeeListByDepId($department['dep_id']));
        if ($department['employees_count'] > 0) {
            return $department;
        }
        $filteredJobVacancies = [];
        foreach ($department->jobVacancies as $jobVacancy) {
            if ($jobVacancy->vacancyStatus['vacancy_status_id'] == 1) {
                $filteredJobVacancies[] = $jobVacancy;
            }
        }
        unset($department['jobVacancies']);
        if (!empty($filteredJobVacancies)) {
            $department['message'] = 'There is one or more opened job vacancies';
            $department['jobVacancies'] = $filteredJobVacancies;
            return $department;
        }
        $department->delete();
        return $department;
    }
}
