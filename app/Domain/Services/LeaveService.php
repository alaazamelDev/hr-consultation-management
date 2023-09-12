<?php

namespace App\Domain\Services;

use App\Domain\Repositories\LeaveRepositoryInterface;
use App\Domain\Models\Leave;
use Illuminate\Database\Eloquent\Builder;

class LeaveService
{
    private LeaveRepositoryInterface $LeaveRepository;

    public function __construct(LeaveRepositoryInterface $LeaveRepository)
    {
        $this->LeaveRepository = $LeaveRepository;
    }

    public function getLeaveList(): array
    {
        return $this->LeaveRepository->getLeaveList();
    }

    public function getLeaveById(int $id): Leave|Builder|null
    {
        return $this->LeaveRepository->getLeaveById($id);
    }

    public function createLeave(array $data): Leave|Builder|null
    {
        return $this->LeaveRepository->createLeave($data);
    }

    public function updateLeave(int $id, array $data): Leave|Builder|null
    {
        return $this->LeaveRepository->updateLeave($id, $data);
    }

    public function deleteLeave($id): Leave|Builder|null
    {
        return $this->LeaveRepository->deleteLeave($id);
    }
}