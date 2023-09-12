<?php

namespace App\Domain\Services;

use App\Domain\Repositories\ClinicRepositoryInterface;
use App\Domain\Models\CD\Clinic;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class ClinicService
{
    private ClinicRepositoryInterface $ClinicRepository;

    public function __construct(ClinicRepositoryInterface $ClinicRepository)
    {
        $this->ClinicRepository = $ClinicRepository;
    }

    public function getClinicList(): Collection
    {
        return $this->ClinicRepository->getClinicList();
    }
}
