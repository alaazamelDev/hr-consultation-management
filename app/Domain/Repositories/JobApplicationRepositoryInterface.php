<?php

namespace App\Domain\Repositories;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

interface JobApplicationRepositoryInterface
{
    public function getJobApplicationsList(): LengthAwarePaginator;

    public function getJobApplicationById(int $id): Builder|array|Collection|Model;

    public function createJobApplication(array $data): Builder|Model;

    public function updateJobApplication(int $id, array $data): Builder|Model;

    public function deleteJobApplications(array $data): array|Collection;

    public function acceptJobApplicationRequest($id): Model|Builder;

    public function rejectJobApplicationRequest($id): Model|Builder;
}
