<?php

namespace App\Infrastructure\Persistence\Eloquent;

use App\Domain\Repositories\ClinicRepositoryInterface;
use App\Domain\Models\CD\Clinic;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class EloquentClinicRepository implements ClinicRepositoryInterface
{
    public function getClinicList(): Collection
    {
        return Clinic::query()->withCount('consultants')->get();
    }
}
