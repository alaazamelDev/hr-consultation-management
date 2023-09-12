<?php

namespace App\Domain\Services;

use App\Domain\Repositories\LogRepositoryInterface;
use App\Domain\Models\Log;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class LogService
{
    private LogRepositoryInterface $LogRepository;

    public function __construct(LogRepositoryInterface $LogRepository)
    {
        $this->LogRepository = $LogRepository;
    }

    public function getAllAction(): Collection
    {
        return $this->LogRepository->getAllAction();
    }

    public function getAllAffectedUser(): LengthAwarePaginator
    {
        return $this->LogRepository->getAllAffectedUser();
    }

    public function getAllUser(): LengthAwarePaginator
    {
        return $this->LogRepository->getAllUser();
    }

    public function getLog(): LengthAwarePaginator
    {
        return $this->LogRepository->getLog();
    }

}
