<?php

namespace App\Domain\Repositories;

use App\Domain\Models\Department;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

interface DepartmentRepositoryInterface
{
    public function getList(): Collection;

    public function getById(int $id): ?Model;

    public function create(array $data): Model;

    public function update(int $id, array $data): Model;

    public function delete($id): Department;
}
