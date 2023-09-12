<?php

namespace App\Infrastructure\Persistence\Eloquent;

use App\Domain\Repositories\LatetimeRepositoryInterface;
use App\Domain\Models\Latetime;
use Illuminate\Database\Eloquent\Builder;

class EloquentLatetimeRepository implements LatetimeRepositoryInterface
{

    public function createLatetime(array $data): Latetime|Builder|null
    {
        $attendanceTime = \DateTime::createFromFormat('H:i:s', $data["attendance_time"]);
        $scheduleTimeIn = \DateTime::createFromFormat('H:i:s', $data["schedule_time_in"]);
        $duration = $attendanceTime->diff($scheduleTimeIn);

        return Latetime::query()->create([
            "emp_id" => $data["emp_id"],
            "duration" => $duration->format('%H:%I:%S'),
            "latetime_date"=>$data["latetime_date"]
        ]);
    }

    public function deleteLatetime($id): Latetime|Builder|null
    {
        $late_time = Latetime::query()->find($id);

        if(!$late_time) return null;

        $late_time->delete();
        return $late_time;
    }

    public function getEmployeeLateByDate($emp_id,$date): Latetime|Builder|null
    {
        $late_time = Latetime::query()->whereDate('latetime_date','=',$date)
            ->where('emp_id','=',$emp_id)->first();

        if(!$late_time) return null;

        return $late_time;
    }
}
