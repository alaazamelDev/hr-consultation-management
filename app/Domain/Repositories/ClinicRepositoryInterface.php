<?php

namespace App\Domain\Repositories;

use App\Domain\Models\CD\Clinic;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

interface ClinicRepositoryInterface
{
    public function getClinicList(): Collection;
}
