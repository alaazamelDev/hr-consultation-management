<?php

namespace App\Infrastructure\Persistence\Eloquent;

use App\Domain\Repositories\PermissionRepositoryInterface;
use App\Domain\Models\Permission;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class EloquentPermissionRepository implements PermissionRepositoryInterface
{
    public function getPermissionList(): Collection
    {
        return Permission::all();
    }

    public function getPermissionById(int $id): Permission|Builder|null
    {
        return Permission::query()->find($id);
    }

}
