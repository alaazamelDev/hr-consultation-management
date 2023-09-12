<?php

namespace App\Domain\Services;

use App\Domain\Repositories\DepartmentRepositoryInterface;
use App\Domain\Models\Department;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class DepartmentService
{
    /** @var DepartmentRepositoryInterface */
    private $DepartmentRepository;

    public function __construct(DepartmentRepositoryInterface $DepartmentRepository)
    {
        $this->DepartmentRepository = $DepartmentRepository;
    }

    public function getList(): Collection
    {
        return $this->DepartmentRepository->getList();
    }

    public function getById(int $id): ?Model
    {
        return $this->DepartmentRepository->getById($id);
    }

    public function create(array $data): Model
    {
        return $this->DepartmentRepository->create($data);
    }

    public function update(int $id, array $data): Model
    {
        return $this->DepartmentRepository->update($id, $data);
    }

    public function delete($id): Model
    {
        return $this->DepartmentRepository->delete($id);
    }
}
