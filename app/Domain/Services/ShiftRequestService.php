<?php

namespace App\Domain\Services;

use App\Domain\Repositories\ShiftRequestRepositoryInterface;
use App\Domain\Models\ShiftRequest;
use App\Exceptions\EntryNotFoundException;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;

class ShiftRequestService
{
    private ShiftRequestRepositoryInterface $ShiftRequestRepository;

    public function __construct(ShiftRequestRepositoryInterface $ShiftRequestRepository)
    {
        $this->ShiftRequestRepository = $ShiftRequestRepository;
    }

    public function getShiftRequestList(): LengthAwarePaginator
    {
        return $this->ShiftRequestRepository->getShiftRequestList();
    }

    public function getShiftRequestById(int $id): Builder|Model
    {
        return $this->ShiftRequestRepository->getShiftRequestById($id);
    }

    public function createShiftRequest(array $data): Builder|Model
    {
        return $this->ShiftRequestRepository->createShiftRequest($data);
    }

    public function updateShiftRequest(int $id, array $data): ShiftRequest
    {
        return $this->ShiftRequestRepository->updateShiftRequest($id, $data);
    }

    public function deleteShiftRequest($id): ShiftRequest
    {
        return $this->ShiftRequestRepository->deleteShiftRequest($id);
    }

    /**
     * @param $id
     * @return ShiftRequest
     * @throws EntryNotFoundException
     */
    public function acceptShiftRequest($id): ShiftRequest
    {
        try {
            return $this->ShiftRequestRepository->acceptShiftRequest($id);
        }
        catch (Exception $exception)
        {
            throw new EntryNotFoundException("Entry with ID $id not found.");
        }
    }

    public function rejectShiftRequest($id): Model|Builder
    {
        return $this->ShiftRequestRepository->rejectShiftRequest($id);
    }
}
