<?php

namespace App\Domain\Services;

use App\Domain\Repositories\HolidayRepositoryInterface;
use App\Domain\Models\Holiday;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class HolidayService
{
    private HolidayRepositoryInterface $HolidayRepository;

    public function __construct(HolidayRepositoryInterface $HolidayRepository)
    {
        $this->HolidayRepository = $HolidayRepository;
    }

    public function getHolidayList(): array
    {
        return $this->HolidayRepository->getHolidayList();
    }

    public function getHolidayById(int $id): Holiday|Builder|null
    {
        return $this->HolidayRepository->getHolidayById($id);
    }

    public function createHoliday(array $data): Holiday|Builder|null
    {
        return $this->HolidayRepository->createHoliday($data);
    }

    public function updateHoliday(int $id, array $data): Holiday|Builder|null
    {
        return $this->HolidayRepository->updateHoliday($id, $data);
    }

    public function deleteHoliday($id): Holiday|Builder|null
    {
        return $this->HolidayRepository->deleteHoliday($id);
    }

    public function getHolidaysByDate($date): Holiday|Builder|null
    {
        return $this->HolidayRepository->getHolidaysByDate($date);
    }
}
