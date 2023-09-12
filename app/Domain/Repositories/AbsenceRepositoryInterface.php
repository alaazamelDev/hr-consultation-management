<?php

namespace App\Domain\Repositories;

use App\Domain\Models\Absence;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

interface AbsenceRepositoryInterface
{
    public function getAbsenceList(): Collection;

    public function getAbsenceById(int $id): Absence|Builder|null;

    public function createAbsence(array $data): Absence|Builder|null;

    public function updateAbsenceStatus($id,$status): Absence|Builder|null;

    public function getEmployeeAbsences($emp_id): Collection|null;
}
