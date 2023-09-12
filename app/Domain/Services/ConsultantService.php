<?php

namespace App\Domain\Services;

use App\Domain\Repositories\ConsultantRepositoryInterface;
use App\Domain\Models\CD\Consultant;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class ConsultantService
{
    private ConsultantRepositoryInterface $ConsultantRepository;

    public function __construct(ConsultantRepositoryInterface $ConsultantRepository)
    {
        $this->ConsultantRepository = $ConsultantRepository;
    }

    public function getConsultantList(): Collection
    {
        return $this->ConsultantRepository->getConsultantList();
    }

    public function getConsultantById(int $id): Consultant|Builder|null
    {
        return $this->ConsultantRepository->getConsultantById($id);
    }

    public function createConsultant(array $data): Consultant|Builder|null
    {
        return $this->ConsultantRepository->createConsultant($data);
    }

    public function updateConsultant(int $id, array $data): Consultant|Builder|null
    {
        return $this->ConsultantRepository->updateConsultant($id, $data);
    }

    public function deleteConsultant($id): Consultant|Builder|null
    {
        return $this->ConsultantRepository->deleteConsultant($id);
    }

    public function getStatistics($id): array|null
    {
        return $this->ConsultantRepository->getStatistics($id);
    }

    public function getMonthlyStatistics($id) : array|null
    {
        return $this->ConsultantRepository->getMonthlyStatistics($id);
    }
}
