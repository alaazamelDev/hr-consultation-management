<?php

namespace App\Domain\Repositories;

use App\Domain\Models\EducationLevel;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

interface EducationLevelRepositoryInterface
{
    public function getEducationLevelList(): Collection;

}
