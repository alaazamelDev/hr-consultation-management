<?php

namespace App\Infrastructure\Persistence\Eloquent;

use App\Domain\Repositories\LeaveRepositoryInterface;
use App\Domain\Models\Leave;
use Illuminate\Database\Eloquent\Builder;

class EloquentLeaveRepository implements LeaveRepositoryInterface
{
    public function getLeaveList(): array
    {
        $leaves = Leave::query()->latest('leave_date')->paginate(10);
        foreach ($leaves as $key => $leave) {
            if (!($leave->schedule_time_out <= $leave->leave_time)) {
                $leaveTime = \DateTime::createFromFormat('H:i:s', $leave["leave_time"]);
                $scheduleTimeOut = \DateTime::createFromFormat('H:i:s', $leave->schedule_time_out);
                $duration = $scheduleTimeOut->diff($leaveTime);
                $leave["leaveBefore"] = $duration->format('%H:%I:%S');
            }
            $leave->employee->load('jobApplication.empData:emp_data_id,first_name,last_name');
        }

        return $leaves->toArray();
    }

    public function getLeaveById(int $id): Leave|Builder|null
    {
        $leave = Leave::query()->find($id);

        if(!$leave) return null;

        if (!($leave->schedule_time_out <= $leave->leave_time)) {
            $leaveTime = \DateTime::createFromFormat('H:i:s', $leave["leave_time"]);
            $scheduleTimeOut = \DateTime::createFromFormat('H:i:s', $leave->schedule_time_out);
            $duration = $scheduleTimeOut->diff($leaveTime);

            $leave["leaveBefore"] = $duration->format('%H:%I:%S');
        }

        $leave->employee->load('jobApplication.empData:emp_data_id,first_name,last_name');
        return $leave;
    }

    public function createLeave(array $data): Leave|Builder|null
    {
        if($leave = Leave::query()->where('emp_id',$data['emp_id'])->where('leave_date',$data['leave_date'])
            ->first()
        )
        {
            $leave['message'] = "Employee has already checked-out in this day";
            return $leave;
        }

        $eloquentAttendanceRepository = new EloquentAttendanceRepository();
        if(!($employee_att = $eloquentAttendanceRepository->getEmployeeAttByDate($data['emp_id'],$data['leave_date']))){
            return null;
        }

        $leave = Leave::query()->create([
            'emp_id' => $data['emp_id'],
            'uid' => $data['uid'] ?? 0,
            'state' => $data['state'] ?? 1,
            'status' => $data['status'] ?? 1,
            'leave_time' => $data['leave_time'],
            'leave_date' => $data['leave_date'],
        ]);

        $leave->schedule_time_in = $leave->employee->schedule->time_in;
        $leave->schedule_time_out = $leave->employee->schedule->time_out;
        $leave->save();

        // Shift request
        $shift_request = $leave->employee->shiftRequests()
            ->where('shift_req_id','=',$employee_att['shift_req_id'])
            ->whereNull('deleted_at')
            ->where('req_stat',2) // TODO check if id is correct
            ->first();

        $shift_new_time_in = null;
        $shift_new_time_out = null;
        if($shift_request){
            $leave->shift_req_id = $shift_request->shift_req_id;
            $leave->save();
            $shift_new_time_in = $shift_request["new_time_in"];
            $shift_new_time_out = $shift_request["new_time_out"];
        }

        $schedule_time_out = $shift_new_time_out ?? $leave->employee->schedule->time_out;

        if (!($schedule_time_out <= $leave->leave_time)) {
            $leave["status"] =0;
            $leave->save();
            $leaveTime = \DateTime::createFromFormat('H:i:s', $leave["leave_time"]);
            $scheduleTimeOut = \DateTime::createFromFormat('H:i:s', $schedule_time_out);
            $duration = $scheduleTimeOut->diff($leaveTime);

            $leave["leaveBefore"] = $duration->format('%H:%I:%S');
        }

        $leave->employee->load('jobApplication.empData:emp_data_id,first_name,last_name');
        return $leave;
    }

    public function updateLeave(int $id, array $data): Leave|Builder|null
    {
        $leave = Leave::query()->find($id);
        if(!$leave) return null;

        $leave['emp_id'] = $data['emp_id'] ?? $leave['emp_id'];
        $leave['state'] = $data['state'] ?? $leave['state'];
        $leave['leave_time'] = $data['leave_time'] ?? $leave['leave_time'];
        $leave['leave_date'] = $data['leave_date'] ?? $leave['leave_date'];
        $leave['status'] = $data['status'] ?? $leave['status'];
        $leave->save();


        if (!($leave->schedule_time_out <= $leave->leave_time)) {
            $leave["status"] =0;
            $leave->save();
            $leaveTime = \DateTime::createFromFormat('H:i:s', $leave["leave_time"]);
            $scheduleTimeOut = \DateTime::createFromFormat('H:i:s', $leave->schedule_time_out);
            $duration = $scheduleTimeOut->diff($leaveTime);

            $leave["leaveBefore"] = $duration->format('%H:%I:%S');
        }
        else{
            $leave["status"] =1;
            $leave->save();
        }
        $leave->employee->load('jobApplication.empData:emp_data_id,first_name,last_name');
        return $leave;
    }

    public function deleteLeave($id): Leave|Builder|null
    {
        $leave = $this->getLeaveById($id);

        if(!$leave) return null;

        $leave->delete();
        return $leave;
    }
}
