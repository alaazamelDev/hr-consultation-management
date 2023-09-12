<?php


namespace App\Domain\Services;


use App\Domain\Repositories\JobApplicationRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Throwable;

class JobApplicationService
{
    private JobApplicationRepositoryInterface $jobApplicationRepository;

    public function __construct(JobApplicationRepositoryInterface $jobApplicationRepository)
    {
        $this->jobApplicationRepository = $jobApplicationRepository;
    }

    public function getJobApplicationsList(): LengthAwarePaginator
    {
        return $this->jobApplicationRepository->getJobApplicationsList();
    }

    /**
     * @throws Throwable
     */
    public function getJobApplicationById(int $id): Builder|Model
    {
            return $this->jobApplicationRepository->getJobApplicationById($id);
    }

    public function createJobApplication(array $data): Builder|Model
    {
        return $this->jobApplicationRepository->createJobApplication($data);
    }

    public function updateJobApplication(int $id, array $data): Builder|Model
    {
        return $this->jobApplicationRepository->updateJobApplication($id, $data);
    }

    public function deleteJobApplications(array $data): array|Collection
    {
        return $this->jobApplicationRepository->deleteJobApplications($data);
    }

    public function acceptJobApplicationRequest($id): Model|Builder
    {
        return $this->jobApplicationRepository->acceptJobApplicationRequest($id);
    }

    public function rejectJobApplicationRequest($id): Model|Builder
    {
        return $this->jobApplicationRepository->rejectJobApplicationRequest($id);
    }
}
