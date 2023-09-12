<?php

namespace App\Domain\Repositories;

use App\Domain\Models\VacationRequest;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;

interface VacationRequestRepositoryInterface
{
    public function getVacationRequestList(): LengthAwarePaginator;

    public function getVacationRequestById(int $id): VacationRequest|Builder|null;

    public function createVacationRequest(array $data): VacationRequest|Builder|null;

    public function updateVacationRequest(int $id, array $data): VacationRequest|Builder|null;

    public function deleteVacationRequest($id): VacationRequest|Builder|null;

    public function acceptVacationRequest($id): VacationRequest|Builder;

    public function rejectVacationRequest($id): VacationRequest|Builder;
}
