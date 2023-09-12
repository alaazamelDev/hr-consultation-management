<?php

namespace App\Domain\Repositories;

use App\Domain\Models\Attendance;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

interface AttendanceRepositoryInterface
{
    public function getAttendanceList(): array;

    public function getAttendanceById(int $id): Attendance|Builder|null;

    public function createAttendance(array $data): Attendance|Builder|null;

    public function updateAttendance(int $id, array $data): Attendance|Builder|null;

    public function deleteAttendance($id): Attendance|Builder|null;

    public function getEmployeeAttByDate($emp_id,$date): Attendance|Builder|null;

    public function getAllEmployeesAttByDate($date): Collection|null;

}
