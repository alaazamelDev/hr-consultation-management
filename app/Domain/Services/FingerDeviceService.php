<?php

namespace App\Domain\Services;

use App\Domain\Models\Attendance;
use App\Domain\Repositories\FingerDeviceRepositoryInterface;
use App\Domain\Models\FingerDevice;
use App\Infrastructure\Persistence\Eloquent\EloquentAttendanceRepository;
use App\Infrastructure\Persistence\Eloquent\EloquentEmployeeRepository;
use App\Infrastructure\Persistence\Eloquent\EloquentLeaveRepository;
use Illuminate\Database\Eloquent\Builder;
use Rats\Zkteco\Lib\ZKTeco;

class FingerDeviceService
{
    private FingerDeviceRepositoryInterface $FingerDeviceRepository;

    public function __construct(FingerDeviceRepositoryInterface $FingerDeviceRepository)
    {
        $this->FingerDeviceRepository = $FingerDeviceRepository;
    }

    public function getFingerDeviceList(): array
    {
        return $this->FingerDeviceRepository->getFingerDeviceList();
    }

    public function getFingerDeviceById(int $id): FingerDevice|null
    {
        return $this->FingerDeviceRepository->getFingerDeviceById($id);
    }

    public function createFingerDevice(array $data): FingerDevice|Builder|null
    {
        return $this->FingerDeviceRepository->createFingerDevice($data);
    }

    public function updateFingerDevice(int $id, array $data): FingerDevice|Builder|null
    {
        return $this->FingerDeviceRepository->updateFingerDevice($id, $data);
    }

    public function deleteFingerDevice($id): FingerDevice|Builder|null
    {
        return $this->FingerDeviceRepository->deleteFingerDevice($id);
    }



    public function addEmployeeToFingerDevice(int $emp_id): bool|null
    {
        $employeeService = new EmployeeService(new EloquentEmployeeRepository());
        $employee = $employeeService->getEmployeeById($emp_id);
        $fingerDevices = $this->getFingerDeviceList();

        if (!$fingerDevices || !$employee) return null;

        foreach ($fingerDevices as $fingerDevice) {

            $device = new ZKTeco($fingerDevice->ip, 4370);
            $device->connect();

            $device->disableDevice();

            $deviceUsers = collect($device->getUser())->pluck('uid');

            $employee_user_name = $employee->user()->username;
            if (!($deviceUsers->contains($employee->emp_id))) {
                $device->setUser($employee->emp_id, $employee->emp_id, $employee_user_name, '', '0', '0');
            }

            $device->enableDevice();
        }

        return true;
    }

    public function deleteEmployeeFromFingerDevice(int $emp_id): bool|null
    {
        $fingerDevices = $this->getFingerDeviceList();

        if (!$fingerDevices) return null;

        foreach ($fingerDevices as $fingerDevice) {

            $device = new ZKTeco($fingerDevice->ip, 4370);
            $device->connect();

            $device->disableDevice();

            $deviceUsers = collect($device->getUser())->pluck('uid');

            if (($deviceUsers->contains($emp_id))) {
                $device->removeUser($emp_id);
            }

            $device->enableDevice();
        }

        return true;
    }

    public function storeAttendanceFromFingerDevices(): bool
    {
        $fingerDeviceList = $this->getFingerDeviceList();
        foreach ($fingerDeviceList as $fingerDevice) {
            $device = new ZKTeco($fingerDevice->ip, 4370);
            $device->connect();

            // Disable the device so that no one can check in OR check out while storing the attendances log
            $device->disableDevice(); // TODO Check this

            // Get attendance log from the device
            $data = $device->getAttendance();


            $employeeService = new EmployeeService(new EloquentEmployeeRepository());
            $attendanceService = new AttendanceService(new EloquentAttendanceRepository());
            $leaveService = new LeaveService(new EloquentLeaveRepository());

            foreach ($data as $key => $value) {

                if( $value['type']==0){
                    if ( $employeeService->getEmployeeById($value["id"]) != null ) {

                        $att["uid"] = $value['uid'];
                        $att["emp_id"] = $value['id'];
                        $att["state"] = $value['state'];
                        $att["attendance_time"] = date('H:i:s', strtotime($value['timestamp']));
                        $att["attendance_date"] = date('Y-m-d', strtotime($value['timestamp']));
                        $att["type"] = $value['type'];

                        $attendanceService->createAttendance($att);
                    }
                }

                else{
                    if ( $employeeService->getEmployeeById($value["id"]) != null ) {

                        $lve["uid"] = $value['uid'];
                        $lve["emp_id"] = $value['id'];
                        $lve["state"] = $value['state'];
                        $lve["leave_time"] = date('H:i:s', strtotime($value['timestamp']));
                        $lve["leave_date"] = date('Y-m-d', strtotime($value['timestamp']));
                        $lve["type"] = $value['type'];

                        $leaveService->createLeave($lve);
                    }
                }
            }


            // Clear attendance log from the device
            $device->clearAttendance();   // TODO Check this

            // Enable the device
            $device->enableDevice();
        }

        return true;
    }

}
