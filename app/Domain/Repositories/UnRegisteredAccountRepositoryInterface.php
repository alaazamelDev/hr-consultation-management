<?php

namespace App\Domain\Repositories;

use App\Domain\Models\CD\UnRegisteredAccount;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;

interface UnRegisteredAccountRepositoryInterface
{
    public function getUnRegisteredAccountList(): LengthAwarePaginator;

    public function getUnRegisteredAccountById(int $id): UnRegisteredAccount|Builder|null;

    public function bookUnRegisteredAccountAppointment(array $data): UnRegisteredAccount|Builder|null;

//    public function updateUnRegisteredAccount(int $id, array $data): UnRegisteredAccount|Builder|null;

    public function deleteUnRegisteredAccount($id): UnRegisteredAccount|Builder|null;
}
