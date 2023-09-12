<?php

namespace App\Domain\Repositories;

use App\Domain\Models\CD\Consultant;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

interface ConsultantRepositoryInterface
{
    public function getConsultantList(): Collection;

    public function getConsultantById(int $id): Consultant|Builder|null;

    public function createConsultant(array $data): Consultant|Builder|null;

    public function updateConsultant(int $id, array $data): Consultant|Builder|null;

    public function deleteConsultant($id): Consultant|Builder|null;

    public function getStatistics($id): array|null;

    public function getMonthlyStatistics($id) : array|null;
}
