<?php

namespace App\Domain\Services;

use App\Domain\Repositories\EducationLevelRepositoryInterface;
use App\Domain\Models\EducationLevel;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class EducationLevelService
{
    private EducationLevelRepositoryInterface $EducationLevelRepository;

    public function __construct(EducationLevelRepositoryInterface $EducationLevelRepository)
    {
        $this->EducationLevelRepository = $EducationLevelRepository;
    }

    public function getEducationLevelList(): Collection
    {
        return $this->EducationLevelRepository->getEducationLevelList();
    }

}
