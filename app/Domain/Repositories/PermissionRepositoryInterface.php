<?php

namespace App\Domain\Repositories;

use App\Domain\Models\Permission;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

interface PermissionRepositoryInterface
{
    public function getPermissionList(): Collection;

    public function getPermissionById(int $id): Permission|Builder|null;

}
