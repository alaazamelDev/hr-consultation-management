<?php

namespace App\Domain\Services;

use App\Domain\Repositories\ScheduleRepositoryInterface;
use App\Domain\Models\Schedule;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class ScheduleService
{
    private ScheduleRepositoryInterface $ScheduleRepository;

    public function __construct(ScheduleRepositoryInterface $ScheduleRepository)
    {
        $this->ScheduleRepository = $ScheduleRepository;
    }

    public function getScheduleList(): Collection
    {
        return $this->ScheduleRepository->getScheduleList();
    }

    public function getScheduleById(int $id): Schedule|Builder|null
    {
        return $this->ScheduleRepository->getScheduleById($id);
    }

    public function createSchedule(array $data): Schedule|Builder|null
    {
        return $this->ScheduleRepository->createSchedule($data);
    }

    public function updateSchedule(int $id, array $data): Schedule|Builder|null
    {
        return $this->ScheduleRepository->updateSchedule($id, $data);
    }

    public function deleteSchedule($id): Schedule|Builder|null
    {
        return $this->ScheduleRepository->deleteSchedule($id);
    }
}
