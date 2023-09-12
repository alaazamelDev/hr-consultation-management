<?php

namespace Tests\Unit;

use App\Domain\Models\Department;
use App\Domain\Services\AbsenceService;
use App\Domain\Services\AttendanceService;
use App\Domain\Services\EmployeeService;
use App\Domain\Services\EmployeeVacationService;
use App\Domain\Services\FingerDeviceService;
use App\Infrastructure\Persistence\Eloquent\EloquentAbsenceRepository;
use App\Infrastructure\Persistence\Eloquent\EloquentAttendanceRepository;
use App\Infrastructure\Persistence\Eloquent\EloquentEmployeeRepository;
use App\Infrastructure\Persistence\Eloquent\EloquentEmployeeVacationRepository;
use App\Infrastructure\Persistence\Eloquent\EloquentFingerDeviceRepository;
use Tests\TestCase;

class UserTest extends TestCase
{

    public function testLateTimeCalculation(){
        $employeeService = new EmployeeService(new EloquentEmployeeRepository());
        $attendanceService = new AttendanceService(new EloquentAttendanceRepository());

        $employees =  $employeeService->getEmployeeList();
        $employee = null;
        foreach ($employees as $item) {
            $employee_att = $attendanceService->getEmployeeAttByDate($item["emp_id"], now()->format('Y-m-d'));
            if (!$employee_att) {
                $employee = $item;
                break;
            }
        }

        if($employee){
            $attendance_time = "07:32:44";
            $attendance_date = now()->format('Y-m-d');
            $attendance = $attendanceService->createAttendance([
                'emp_id' => $employee['emp_id'],
                'attendance_time' => $attendance_time,
                'attendance_date' => $attendance_date
            ]);

            $expected_late_time = "00:32:44";
            $this->assertEquals($expected_late_time,$attendance["latetime_duration"]);

            $attendance->delete();
            // TODO Delete late time record also
        }


    }

    public function testDeleteVacationAlreadyStarted(){
        $employeeService = new EmployeeService(new EloquentEmployeeRepository());
        $employeeVacationService = new EmployeeVacationService(new EloquentEmployeeVacationRepository());

        $employees =  $employeeService->getEmployeeList();
        $employee = null;
        foreach ($employees as $item) {
            $employee_vacations = $employeeVacationService->getEmployeeVacations($item["emp_id"]);
            $filteredVacations = array_filter($employee_vacations, function ($vacation) {
                return $vacation['start_date'] == now()->format('Y-m-d');
            });
            if (!$filteredVacations) {
                $employee = $item;
                break;
            }
        }

        if($employee) {
            $yesterday = date('Y-m-d', strtotime('-1 day'));
            $total_days =20;
            $employee_vacation = $employeeVacationService->createEmployeeVacation([
                'emp_id' =>$employee["emp_id"],
                'start_date' => $yesterday,
                'total_days' =>$total_days,
                'remaining_days' =>$total_days-1,
            ]);

            $result = $employeeVacationService->deleteEmployeeVacation($employee_vacation->employee_vacation_id);

            $this->assertEquals("vacation can't be deleted , its already started !!",$result["message"]);

            $employee_vacation->delete();
        }
    }

    public function testRegisterAbsenceForEmployeeOnVacation(){
        $employeeService = new EmployeeService(new EloquentEmployeeRepository());
        $employeeVacationService = new EmployeeVacationService(new EloquentEmployeeVacationRepository());

        $employees =  $employeeService->getEmployeeList();
        $employee = null;
        $vacations = null;
        foreach ($employees as $item) {
            $employee_vacations = $employeeVacationService->getEmployeeVacations($item["emp_id"]);
            $filteredVacations = array_filter($employee_vacations, function ($vacation) {
                return $vacation['remaining_days'] != 0;
            });
            if ($filteredVacations) {
                $vacations = $filteredVacations;
                $employee = $item;
                break;
            }
        }

        if($employee){
            $absenceService = new AbsenceService(new EloquentAbsenceRepository());

            $employee_absence = $absenceService->createAbsence([
                'emp_id' =>$employee['emp_id'],
                'absence_date' => $vacations[0]["start_date"]
            ]);

            $this->assertEquals("employee is on vacation",$employee_absence["message"]);

        }
    }

    public function testDepartmentCreation()
    {
        $department_name = "SWE DEPARTMENT";
        $department_description = "software engineering department";

        $department = new Department();
        $department->name = $department_name;
        $department->description = $department_description;

        $this->assertEquals($department_name,$department->name);
        $this->assertEquals($department_description,$department->description);

    }

    public function testStoreAttendanceValidationMissingEmpId()
    {
        $data = [
            'attendance_time' => '09:00:00',
            'attendance_date' => '2023-06-22',
        ];

        $response = $this->postJson('/api/attendance', $data);

        $response->assertStatus(400);
    }


    public function testStoreAttendanceFromFingerDevices()
    {
        $fingerDeviceService = new FingerDeviceService(new EloquentFingerDeviceRepository());
        $result = $fingerDeviceService->storeAttendanceFromFingerDevices();

        $this->assertTrue($result);
    }

}
