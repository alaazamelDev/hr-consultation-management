<?php

namespace App\Domain\Services;

use App\Domain\Repositories\VacationRequestRepositoryInterface;
use App\Domain\Models\VacationRequest;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;

class VacationRequestService
{
    private VacationRequestRepositoryInterface $VacationRequestRepository;

    public function __construct(VacationRequestRepositoryInterface $VacationRequestRepository)
    {
        $this->VacationRequestRepository = $VacationRequestRepository;
    }

    public function getVacationRequestList(): LengthAwarePaginator
    {
        return $this->VacationRequestRepository->getVacationRequestList();
    }

    public function getVacationRequestById(int $id): VacationRequest
    {
        return $this->VacationRequestRepository->getVacationRequestById($id);
    }

    public function createVacationRequest(array $data): VacationRequest
    {
        return $this->VacationRequestRepository->createVacationRequest($data);
    }

    public function updateVacationRequest(int $id, array $data): VacationRequest
    {
        return $this->VacationRequestRepository->updateVacationRequest($id, $data);
    }

    public function deleteVacationRequest($id): VacationRequest
    {
        return $this->VacationRequestRepository->deleteVacationRequest($id);
    }

    public function acceptVacationRequest($id): VacationRequest
    {
        return $this->VacationRequestRepository->acceptVacationRequest($id);
    }

    public function rejectVacationRequest($id): VacationRequest
    {
        return $this->VacationRequestRepository->rejectVacationRequest($id);
    }
}
