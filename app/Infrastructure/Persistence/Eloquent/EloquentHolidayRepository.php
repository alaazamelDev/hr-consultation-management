<?php

namespace App\Infrastructure\Persistence\Eloquent;

use App\Domain\Repositories\HolidayRepositoryInterface;
use App\Domain\Models\Holiday;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class EloquentHolidayRepository implements HolidayRepositoryInterface
{
    public function getHolidayList(): array
    {
        return Holiday::all()->toArray();
    }

    public function getHolidayById(int $id): Holiday|Builder|null
    {
        return Holiday::query()->find($id);
    }

    public function createHoliday(array $data): Holiday|Builder|null
    {
        return Holiday::query()->create([
            "name" => $data["name"],
            "date" => $data["date"],
            "is_recurring" => $data["is_recurring"] ?? 1
        ]);
    }

    public function updateHoliday(int $id, array $data): Holiday|Builder|null
    {
        $holiday = Holiday::query()->find($id);

        if(!$holiday) return null;

        $holiday->name = $data["name"] ?? $holiday["name"];
        $holiday->date = $data["date"] ?? $holiday["date"];
        $holiday->is_recurring = $data["is_recurring"] ?? $holiday["is_recurring"];

        $holiday->save();

        return $holiday;
    }

    public function deleteHoliday($id): Holiday|Builder|null
    {
        $holiday = Holiday::query()->find($id);

        if(!$holiday) return null;

        $holiday->delete();
        return $holiday;
    }

    public function getHolidaysByDate($date): Holiday|Builder|null
    {
        return Holiday::query()->where('date',$date)->first();
    }
}
