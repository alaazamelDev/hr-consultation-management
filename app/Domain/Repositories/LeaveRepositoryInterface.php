<?php

namespace App\Domain\Repositories;

use App\Domain\Models\Leave;
use Illuminate\Database\Eloquent\Builder;

interface LeaveRepositoryInterface
{
    public function getLeaveList(): array;

    public function getLeaveById(int $id): Leave|Builder|null;

    public function createLeave(array $data): Leave|Builder|null;

    public function updateLeave(int $id, array $data): Leave|Builder|null;

    public function deleteLeave($id): Leave|Builder|null;
}