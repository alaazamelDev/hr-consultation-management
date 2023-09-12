<?php

namespace App\Domain\Repositories;

use App\Domain\Models\Schedule;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

interface ScheduleRepositoryInterface
{
    public function getScheduleList(): Collection;

    public function getScheduleById(int $id): Schedule|Builder|null;

    public function createSchedule(array $data): Schedule|Builder|null;

    public function updateSchedule(int $id, array $data): Schedule|Builder|null;

    public function deleteSchedule($id): Schedule|Builder|null;
}
