<?php

namespace App\Domain\Repositories;

use App\Domain\Models\Log;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

interface LogRepositoryInterface
{
    public function getAllAction(): Collection;

    public function getAllAffectedUser(): LengthAwarePaginator;

    public function getAllUser(): LengthAwarePaginator;

    public function getLog(): LengthAwarePaginator;
}
