<?php

namespace App\Domain\Services;

use App\Domain\Repositories\JobVacancyRepositoryInterface;
use App\Domain\Models\JobVacancy;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;

class JobVacancyService
{
    /** @var JobVacancyRepositoryInterface */
    private JobVacancyRepositoryInterface $jobVacancyRepository;

    public function __construct(JobVacancyRepositoryInterface $JobVacancyRepository)
    {
        $this->jobVacancyRepository = $JobVacancyRepository;
    }

    public function getJobVacancyList(): LengthAwarePaginator
    {
        return $this->jobVacancyRepository->getJobVacancyList();
    }

    public function getJobVacancyById(int $id): JobVacancy|Builder|null
    {
        return $this->jobVacancyRepository->getJobVacancyById($id);
    }

    public function createJobVacancy(array $data): JobVacancy|Builder
    {
        return $this->jobVacancyRepository->createJobVacancy($data);
    }

    public function updateJobVacancy(int $id, array $data): JobVacancy|Builder
    {
        return $this->jobVacancyRepository->updateJobVacancy($id, $data);
    }

    public function deleteJobVacancy($id): JobVacancy|Builder
    {
        return $this->jobVacancyRepository->deleteJobVacancy($id);
    }
}
