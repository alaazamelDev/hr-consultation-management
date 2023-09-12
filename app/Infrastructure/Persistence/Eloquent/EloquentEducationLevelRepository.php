<?php

namespace App\Infrastructure\Persistence\Eloquent;

use App\Domain\Repositories\EducationLevelRepositoryInterface;
use App\Domain\Models\EducationLevel;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class EloquentEducationLevelRepository implements EducationLevelRepositoryInterface
{
    public function getEducationLevelList(): Collection
    {
        return EducationLevel::query()->get();
    }

}
