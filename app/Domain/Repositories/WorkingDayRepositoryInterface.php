<?php

namespace App\Domain\Repositories;

use App\Domain\Models\WorkingDay;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

interface WorkingDayRepositoryInterface
{
    public function getWorkingDayList(): Collection;

    public function updateWorkingDay(int $id, array $data): WorkingDay|Builder|null;

}
