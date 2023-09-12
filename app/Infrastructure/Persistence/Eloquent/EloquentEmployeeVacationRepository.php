<?php

namespace App\Infrastructure\Persistence\Eloquent;

use App\Domain\Repositories\EmployeeVacationRepositoryInterface;
use App\Domain\Models\EmployeeVacation;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class EloquentEmployeeVacationRepository implements EmployeeVacationRepositoryInterface
{
    public function getEmployeeVacationList(): Collection
    {
        $results = EmployeeVacation::query()
            ->with('employee');

        // Filter by total days
        if (request()->has('total_days_desc')) {
            $total_days_desc = request()->get('total_days_desc');
            if ($total_days_desc == "true") {
                $results->orderByDesc('total_days');
            } elseif ($total_days_desc == "false") {
                $results->orderBy('total_days');
            }
            else
                $results->orderBy('start_date', 'desc');
        }
        elseif (request()->has('remaining_days_desc')) {
            $remaining_days_desc = request()->get('remaining_days_desc');
            if ($remaining_days_desc == "true") {
                $results->orderByDesc('remaining_days');
            } elseif ($remaining_days_desc == "false") {
                $results->orderBy('remaining_days');
            }
            else
                $results->orderBy('start_date', 'desc');
        }

        else
            $results->orderBy('start_date', 'desc');


        // search by name (full name)
        if (request()->has('name')) {

            // get the name
            $name = request()->get('name');

            // trim & convert to lowercase
            $name = strtolower(trim($name));

            // search after ignoring the case
            $results->whereHas('employee.jobApplication', function ($query) use ($name) {
                $query->whereHas('empData', function ($query) use ($name) {
                    $query->whereRaw('LOWER(first_name) LIKE ?', ["%$name%"])
                        ->orWhereRaw('LOWER(last_name) LIKE ?', ["%$name%"])
                        ->orWhereRaw('CONCAT(LOWER(first_name), " ", LOWER(last_name)) LIKE ?', ["%$name%"]);
                });
            });
        }

        // filter by emp_id
        if (request()->has('emp_id')) {

            // get the emp_ids
            $emp_ids = request()->get('emp_id');

            // extract the comma separated values
            $emp_ids = explode(',', $emp_ids);

            // convert it to array of integers
            $emp_ids = array_map('intval', $emp_ids);

            // filter the result based on $emp_ids
            $results->whereHas('employee', function ($query) use ($emp_ids) {
                $query->whereIn('emp_id', $emp_ids);
            })->get();
        }

        // Search by date
        if (request()->has('date')) {
            // Get the date
            $date = request()->get('date');

            // Trim and format the date if necessary
            $date = trim($date);

            // Search by the formatted date
            $results->whereDate('start_date', '=', $date)->get();
        }

        $results = $results->paginate(10);

        $modifiedResults = $results->getCollection()->map(function ($employee_vacation) {
            $employee_vacation->employee->full_name = $employee_vacation->employee->getFullNameAttribute();

            $cur_dep = $employee_vacation->employee->getCurrentDepartmentAttribute();
            if ($cur_dep !== null) {
                $employee_vacation->employee->cur_dep = $cur_dep->name;
            }

            $cur_title = $employee_vacation->employee->getCurrentJobTitleAttribute();
            if ($cur_title !== null) {
                $employee_vacation->employee->cur_title = $cur_title->name;
            }

            return $employee_vacation;
        });

        $results->setCollection($modifiedResults);

        return collect($results);

    }

    public function getEmployeeVacationById(int $id): EmployeeVacation|Builder|null
    {
        $employee_vacation = EmployeeVacation::query()
            ->with('employee')
            ->find($id);

        if(!$employee_vacation)
            return null;


        $cur_dep = $employee_vacation->employee->getCurrentDepartmentAttribute();
        if ($cur_dep !== null) {
            $employee_vacation->employee->cur_dep = $cur_dep->name;
        }

        $cur_dep = $employee_vacation->employee->getCurrentDepartmentAttribute();
        if ($cur_dep !== null) {
            $employee_vacation->employee->cur_dep = $cur_dep->name;
        }

        $cut_title = $employee_vacation->employee->getCurrentJobTitleAttribute();
        if($cut_title !== null){
            $employee_vacation->employee->cur_title = $cut_title->name;
        }

        return $employee_vacation;
    }

    public function createEmployeeVacation(array $data): EmployeeVacation|Builder|null
    {
        $employee_vacation = EmployeeVacation::query()->with('employee')->create([
            "emp_id" => $data["emp_id"],
            "start_date" => $data["start_date"],
            "total_days" => $data["total_days"],
            "remaining_days" => $data["remaining_days"] ?? $data["total_days"]
        ]);

        $cur_dep = $employee_vacation->employee->getCurrentDepartmentAttribute();
        if ($cur_dep !== null) {
            $employee_vacation->employee->cur_dep = $cur_dep->name;
        }

        $cur_dep = $employee_vacation->employee->getCurrentDepartmentAttribute();
        if ($cur_dep !== null) {
            $employee_vacation->employee->cur_dep = $cur_dep->name;
        }

        $cut_title = $employee_vacation->employee->getCurrentJobTitleAttribute();
        if($cut_title !== null){
            $employee_vacation->employee->cur_title = $cut_title->name;
        }

        return $employee_vacation;
    }

    public function updateEmployeeVacation(int $id, array $data): EmployeeVacation|Builder|null
    {
        $employee_vacation = EmployeeVacation::query()->find($id);

        if(!$employee_vacation) return null;

        $employee_vacation["emp_id"] = $data["emp_id"] ?? $employee_vacation["emp_id"];
        $employee_vacation["start_date"] = $data["start_date"] ?? $employee_vacation["start_date"];
        $employee_vacation["total_days"] = $data["total_days"] ?? $employee_vacation["total_days"];
        $employee_vacation["remaining_days"] = $data["remaining_days"] ?? $employee_vacation["remaining_days"];
        $employee_vacation->save();

        $employee_vacation->employee();
        $cur_dep = $employee_vacation->employee->getCurrentDepartmentAttribute();
        if ($cur_dep !== null) {
            $employee_vacation->employee->cur_dep = $cur_dep->name;
        }

        $cur_dep = $employee_vacation->employee->getCurrentDepartmentAttribute();
        if ($cur_dep !== null) {
            $employee_vacation->employee->cur_dep = $cur_dep->name;
        }

        $cut_title = $employee_vacation->employee->getCurrentJobTitleAttribute();
        if($cut_title !== null){
            $employee_vacation->employee->cur_title = $cut_title->name;
        }

        return $employee_vacation;
    }

    public function deleteEmployeeVacation($id): EmployeeVacation|Builder|null
    {
        $employee_vacation = EmployeeVacation::query()->with('employee')->find($id);

        if(!$employee_vacation) return null;

        if($employee_vacation["remaining_days"] != $employee_vacation["total_days"]){
            $employee_vacation["message"] = "vacation can't be deleted , its already started !!";
            return $employee_vacation;
        }

        $cur_dep = $employee_vacation->employee->getCurrentDepartmentAttribute();
        if ($cur_dep !== null) {
            $employee_vacation->employee->cur_dep = $cur_dep->name;
        }

        $cur_dep = $employee_vacation->employee->getCurrentDepartmentAttribute();
        if ($cur_dep !== null) {
            $employee_vacation->employee->cur_dep = $cur_dep->name;
        }

        $cut_title = $employee_vacation->employee->getCurrentJobTitleAttribute();
        if($cut_title !== null){
            $employee_vacation->employee->cur_title = $cut_title->name;
        }

        $employee_vacation->delete();
        return $employee_vacation;
    }

    public function getEmployeeVacations($emp_id): array
    {
        $eloquentEmployeeRepository = new EloquentEmployeeRepository();
        $employee = $eloquentEmployeeRepository->getEmployeeById($emp_id);

        if(!$employee) return ["message"=>'employee not found'];
        return EmployeeVacation::query()->where('emp_id',$emp_id)->latest('start_date')->get()->toArray();
    }
}
