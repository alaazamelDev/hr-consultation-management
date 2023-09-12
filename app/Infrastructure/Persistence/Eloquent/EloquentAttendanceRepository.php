<?php

namespace App\Infrastructure\Persistence\Eloquent;

use App\Domain\Repositories\AttendanceRepositoryInterface;
use App\Domain\Models\Attendance;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class EloquentAttendanceRepository implements AttendanceRepositoryInterface
{
    public function getAttendanceList(): array
    {
        $attendance =  Attendance::query()
            ->with([
                'employee:emp_id,schedule_id,cur_dep,job_app_id',
                'employee.schedule:schedule_id,name,time_in,time_out',
                'employee.jobApplication.empData:emp_data_id,first_name,last_name',
            ])
            ->select(
                'attendances.attendance_id',
                'attendances.emp_id',
                'attendances.attendance_date',
                'attendances.state AS check_in.state',
                'attendances.status AS check_in.status',
                'attendances.attendance_time AS check_in_time',
                'attendances.schedule_time_in', // this new
                'attendances.schedule_time_out', // this new

                'leaves.state AS check_out.state',
                'leaves.status AS check_out.status',
                'leaves.leave_time AS check_out_time',

                'latetimes.duration AS latetime.duration',
                'latetimes.latetime_date AS latetime.latetime_date',

                'shift_requests.new_time_in AS shift.new_time_in',
                'shift_requests.new_time_out AS shift.new_time_out',
            )
            ->leftJoin('leaves', function ($join) {
                $join->on('attendances.emp_id', '=', 'leaves.emp_id')
                    ->whereRaw('leaves.leave_date = attendances.attendance_date')
                    ->whereNull('leaves.deleted_at');
            })
            ->leftJoin('latetimes', function ($join) {
                $join->on('attendances.emp_id', '=', 'latetimes.emp_id')
                    ->whereRaw('latetimes.latetime_date = attendances.attendance_date')
                    ->whereNull('latetimes.deleted_at');
            })
            ->leftJoin('shift_requests', function($join) {
                $join->on('shift_requests.shift_req_id', '=' ,'attendances.shift_req_id')
                    ->whereNull('shift_requests.deleted_at')
                    ->where('shift_requests.req_stat',2);
            })
            ->latest('attendances.attendance_date');

        // search by name (full name)
        if (request()->has('name')) {

            // get the name
            $name = request()->get('name');

            // trim & convert to lowercase
            $name = strtolower(trim($name));

            // search after ignoring the case
            $attendance->whereHas('employee.jobApplication', function ($query) use ($name) {
                $query->whereHas('empData', function ($query) use ($name) {
                    $query->whereRaw('LOWER(first_name) LIKE ?', ["%$name%"])
                        ->orWhereRaw('LOWER(last_name) LIKE ?', ["%$name%"])
                        ->orWhereRaw('CONCAT(LOWER(first_name), " ", LOWER(last_name)) LIKE ?', ["%$name%"]);
                });
            });
        }

//        // filter by scheduleId
//        if (request()->has('schedule')) {
//
//            // get the schedules
//            $schedules = request()->get('schedule');
//
//            // extract the comma separated values
//            $schedules = explode(',', $schedules);
//
//            // convert it to array of integers
//            $schedules = array_map('intval', $schedules);
//
//            // filter the result based on schedule IDs
//            $attendance->whereHas('employee.schedule', function ($query) use ($schedules) {
//                $query->whereIn('schedule_id', $schedules);
//            })->get();
//        }

        // filter by emp_id
        if (request()->has('emp_id')) {

            // get the emp_ids
            $emp_ids = request()->get('emp_id');

            // extract the comma separated values
            $emp_ids = explode(',', $emp_ids);

            // convert it to array of integers
            $emp_ids = array_map('intval', $emp_ids);

            // filter the result based on $emp_ids
            $attendance->whereHas('employee', function ($query) use ($emp_ids) {
                $query->whereIn('emp_id', $emp_ids);
            })->get();
        }

        return $attendance->paginate(10)->toArray();
    }

    // Delete this function (unused)
    public function getAttendanceById(int $id): Attendance|Builder|null
    {
        $attendance = Attendance::query()->with([
            'employee:emp_id,schedule_id,cur_dep,job_app_id',
            'employee.schedule:schedule_id,name,time_in,time_out',
            'employee.jobApplication.empData:emp_data_id,first_name,last_name',
        ])
            ->find($id);

        if(!$attendance) return null;

        // Shift request
        $shift_request = $attendance->employee->shiftRequests()
            ->where('shift_req_id','=',$attendance["shift_req_id "])
            ->whereNull('deleted_at')
            ->where('req_stat',2) // TODO check if id is correct
            ->first();

        if($shift_request){
            $attendance["shift.new_time_in"] = $shift_request["new_time_in"] ;
            $attendance["shift.new_time_out"] = $shift_request["new_time_out"];
        }

        $eloquentLatetimeRepository = new EloquentLatetimeRepository();
        $late_time = $eloquentLatetimeRepository->getEmployeeLateByDate($attendance["emp_id"],$attendance["attendance_date"]);
        if($late_time){
            $attendance["latetime_id"]=$late_time["latetime_id"];
            $attendance["latetime_duration"]=$late_time["duration"];
            $attendance["latetime_date"]=$late_time["latetime_date"];
        }

        return $attendance;
    }

    // Delete this function (unused)
    public function getAttendanceByEmpId(int $emp_id): array|null
    {
        $attendances = Attendance::query()
            ->with([
                'employee:emp_id,schedule_id,cur_dep,job_app_id',
                'employee.schedule:schedule_id,name,time_in,time_out',
                'employee.jobApplication.empData:emp_data_id,first_name,last_name',
            ])
            ->select(
                'attendances.attendance_id',
                'attendances.emp_id',
                'attendances.attendance_date',
                'attendances.state AS check_in.state',
                'attendances.status AS check_in.status',
                'attendances.attendance_time AS check_in_time',
                'attendances.schedule_time_in', // this new
                'attendances.schedule_time_out', // this new

                'leaves.state AS check_out.state',
                'leaves.status AS check_out.status',
                'leaves.leave_time AS check_out_time',
                'latetimes.duration AS latetime.duration',
                'latetimes.latetime_date AS latetime.latetime_date',

                'attendances.shift_req_id',
                'shift_requests.new_time_in AS shift.new_time_in',
                'shift_requests.new_time_out AS shift.new_time_out',
            )
            ->leftJoin('leaves', function ($join) {
                $join->on('attendances.emp_id', '=', 'leaves.emp_id')
                    ->whereRaw('leaves.leave_date = attendances.attendance_date')
                    ->whereNull('leaves.deleted_at');
            })
            ->leftJoin('latetimes', function ($join) {
                $join->on('attendances.emp_id', '=', 'latetimes.emp_id')
                    ->whereRaw('latetimes.latetime_date = attendances.attendance_date')
                    ->whereNull('latetimes.deleted_at');
            })
            ->leftJoin('shift_requests', function($join) {
                $join->on('attendances.emp_id', '=' ,'shift_requests.emp_id')
                    ->whereRaw('shift_requests.start_date = attendances.attendance_date')
                    ->whereNull('shift_requests.deleted_at')
                    ->where('shift_requests.req_stat',2); // TODO check if id is correct
            })
            ->where('attendances.emp_id', $emp_id)
            ->latest('attendances.attendance_date')
            ->paginate(10)->toArray();

        if(!$attendances) return null;


        return $attendances;
    }

    public function createAttendance(array $data): Attendance|Builder|null
    {

        if($att = Attendance::query()->where('emp_id',$data['emp_id'])->where('attendance_date',$data['attendance_date'])
            ->first()
        )
        {
            $att['message'] = "Employee has already checked-in in this day";
            return $att;
        }


        $attendance = Attendance::query()->create([
            'emp_id' => $data['emp_id'],
            'uid' => $data['uid'] ?? 0,
            'state' => $data['state'] ?? 0,
            'status' => $data['status'] ?? 1,
            'attendance_time' => $data['attendance_time'],
            'attendance_date' => $data['attendance_date'],
        ]);

        $attendance->schedule_time_in = $attendance->employee->schedule->time_in;
        $attendance->schedule_time_out = $attendance->employee->schedule->time_out;
        $attendance->save();

        // Shift request
        $shift_request = $attendance->employee->shiftRequests()
            ->whereDate('start_date', '<=', $attendance["attendance_date"])
            ->where('remaining_days','!=',0)
            ->whereNull('deleted_at')
            ->where('req_stat',2)
            ->first();


        $shift_new_time_in = null;
        $shift_new_time_out = null;
        if($shift_request){
            if($shift_request['remaining_days'] != 0) {
                $shift_request->remaining_days -=1;
                $shift_request->save();
                $attendance->shift_req_id = $shift_request->shift_req_id;
                $attendance->save();
                $shift_new_time_in = $shift_request["new_time_in"];
                $shift_new_time_out = $shift_request["new_time_out"];
            }
        }

        // Calculate Late time if exists
        $eloquentLatetimeRepository = new EloquentLatetimeRepository();
        if( $shift_new_time_in !== null){
            $schedule_time_in = $shift_new_time_in;
        }
        else{
            $schedule_time_in = $attendance->employee->schedule->time_in;
        }
        if (!($schedule_time_in >= $attendance["attendance_time"])) {
            $attendance["status"] = 0;
            $attendance->save();
            $late_time = $eloquentLatetimeRepository->createLatetime([
                "emp_id" => $attendance["employee"]["emp_id"],
                "attendance_time" => $attendance["attendance_time"],
                "schedule_time_in" => $schedule_time_in,
                "latetime_date" => $attendance["attendance_date"]
            ]);

            $attendance["latetime_duration"]=$late_time["duration"];
            $attendance["latetime_date"]=$late_time["latetime_date"];
        }

        if($shift_new_time_in !== null){
            $attendance["shift.new_time_in"] = $shift_new_time_in;
            $attendance["shift.new_time_out"] = $shift_new_time_out;
        }

        return $attendance;
    }

    // Delete this function (unused)
    public function updateAttendance(int $id, array $data): Attendance|Builder|null
    {
        $attendance = Attendance::query()->find($id);
        if(!$attendance) return null;

        $attendance['emp_id'] = $data['emp_id'] ?? $attendance['emp_id'];
        $attendance['state'] = $data['state'] ?? $attendance['state'];
        $attendance['attendance_time'] = $data['attendance_time'] ?? $attendance['attendance_time'];
        $attendance['attendance_date'] = $data['attendance_date'] ?? $attendance['attendance_date'];
        $attendance['status'] = $data['status'] ?? $attendance['status'];
        $attendance->save();


        // Shift request
        $shift_request = $attendance->employee->shiftRequests()
            ->whereDate('start_date', '<=', $attendance["attendance_date"])
            ->where('remaining_days','!=',0)
            ->whereNull('deleted_at')
            ->where('req_stat',2) // TODO check if id is correct
            ->first();

        $shift_new_time_in = null;
        $shift_new_time_out = null;
        if($shift_request){
            if($shift_request['remaining_days'] != 0) {
                $shift_request->remaining_days -=1;
                $shift_request->save();
                $shift_new_time_in = $shift_request["new_time_in"];
                $shift_new_time_out = $shift_request["new_time_out"];
            }
        }

        // Calculate Late time if exists
        $eloquentLatetimeRepository = new EloquentLatetimeRepository();
        if( $shift_new_time_in !== null){
            $schedule_time_in = $shift_new_time_in;
        }
        else{
            $schedule_time_in = $attendance->employee->schedule->time_in;
        }
        if(isset($data['attendance_time'])) {
            $late_time = $eloquentLatetimeRepository->getEmployeeLateByDate($attendance["emp_id"],$attendance["attendance_date"]);
            if (!($schedule_time_in >= $data['attendance_time']))
            {
                if($late_time){
                    $eloquentLatetimeRepository->deleteLatetime($late_time->latetime_id);
                }

                $attendance["status"] = 0;
                $attendance->save();
                $late_time = $eloquentLatetimeRepository->createLatetime([
                    "emp_id" => $attendance["employee"]["emp_id"],
                    "attendance_time" => $data['attendance_time'],
                    "schedule_time_in" => $schedule_time_in,
                    "latetime_date" => $attendance["attendance_date"]
                ]);

                $attendance["latetime_duration"]=$late_time["duration"];
                $attendance["latetime_date"]=$late_time["latetime_date"];
            }
            else
            {
                if($late_time) {
                    $attendance["status"]=1;
                    $eloquentLatetimeRepository->deleteLatetime($late_time->latetime_id);
                    $attendance->save();
                }
            }

        }

        if($shift_new_time_in !== null){
            $attendance["shift.new_time_in"] = $shift_new_time_in;
            $attendance["shift.new_time_out"] = $shift_new_time_out;
        }

        return $attendance;
    }

    // Delete this function (unused)
    public function deleteAttendance($id): Attendance|Builder|null
    {
        $attendance = $this->getAttendanceById($id);

        if(!$attendance) return null;

        $attendance->delete();
        $eloquentLatetimeRepository = new EloquentLatetimeRepository();
        $eloquentLatetimeRepository->deleteLatetime($attendance['latetime_id']);

        return $attendance;
    }

    public function getEmployeeAttByDate($emp_id,$date): Attendance|Builder|null
    {
        $attendance = Attendance::query()->whereDate('attendance_date',$date)
            ->where('emp_id','=',$emp_id)->first();

        if(!$attendance) return null;


        return $attendance;
    }

    public function getAllEmployeesAttByDate($date): Collection|null
    {
        return Attendance::query()->with('employee')
        ->where('attendance_date',$date)->latest('attendance_time')->get();
    }
}
