<?php

namespace App\Domain\Repositories;

use App\Domain\Models\JobTitle;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

interface JobTitleRepositoryInterface
{
    public function getJobTitleList(): Collection;

    public function getJobTitleById(int $id): JobTitle|Builder|null;

    public function createJobTitle(array $data): JobTitle|Builder|null;

    public function updateJobTitle(int $id, array $data): JobTitle|Builder|null;

    public function deleteJobTitle($id): JobTitle|Builder|null;
}
