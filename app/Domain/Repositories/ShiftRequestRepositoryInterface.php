<?php

namespace App\Domain\Repositories;

use App\Domain\Models\ShiftRequest;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
interface ShiftRequestRepositoryInterface
{
    public function getShiftRequestList(): LengthAwarePaginator;

    public function getShiftRequestById(int $id): Builder|Model|null;

    public function createShiftRequest(array $data): ShiftRequest|Builder|null;

    public function updateShiftRequest(int $id, array $data): ShiftRequest|Builder|null;

    public function deleteShiftRequest($id): ShiftRequest|Builder|null;

    public function acceptShiftRequest(int $id): ShiftRequest|Builder|null;

    public function rejectShiftRequest(int $id): ShiftRequest|Builder|null;
}
