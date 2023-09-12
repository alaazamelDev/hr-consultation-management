<?php

namespace App\Domain\Services;

use App\Domain\Repositories\JobTitleRepositoryInterface;
use App\Domain\Models\JobTitle;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class JobTitleService
{
    /** @var JobTitleRepositoryInterface */
    private $JobTitleRepository;

    public function __construct(JobTitleRepositoryInterface $JobTitleRepository)
    {
        $this->JobTitleRepository = $JobTitleRepository;
    }

    public function getJobTitleList(): Collection
    {
        return $this->JobTitleRepository->getJobTitleList();
    }

    public function getJobTitleById(int $id): JobTitle|Builder|null
    {
        return $this->JobTitleRepository->getJobTitleById($id);
    }

    public function createJobTitle(array $data): JobTitle|Builder|null
    {
        return $this->JobTitleRepository->createJobTitle($data);
    }

    public function updateJobTitle(int $id, array $data): JobTitle|Builder|null
    {
        return $this->JobTitleRepository->updateJobTitle($id, $data);
    }

    public function deleteJobTitle($id): JobTitle|Builder|null
    {
        return $this->JobTitleRepository->deleteJobTitle($id);
    }
}
