<?php

namespace App\Domain\Services;

use App\Domain\Repositories\UnRegisteredAccountRepositoryInterface;
use App\Domain\Models\CD\UnRegisteredAccount;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;

class UnRegisteredAccountService
{
    private UnRegisteredAccountRepositoryInterface $UnRegisteredAccountRepository;

    public function __construct(UnRegisteredAccountRepositoryInterface $UnRegisteredAccountRepository)
    {
        $this->UnRegisteredAccountRepository = $UnRegisteredAccountRepository;
    }

    public function getUnRegisteredAccountList(): LengthAwarePaginator
    {
        return $this->UnRegisteredAccountRepository->getUnRegisteredAccountList();
    }

    public function getUnRegisteredAccountById(int $id): UnRegisteredAccount|Builder|null
    {
        return $this->UnRegisteredAccountRepository->getUnRegisteredAccountById($id);
    }

    public function createUnRegisteredAccount(array $data): UnRegisteredAccount|Builder|null
    {
        return $this->UnRegisteredAccountRepository->bookUnRegisteredAccountAppointment($data);
    }

//    public function updateUnRegisteredAccount(int $id, array $data): UnRegisteredAccount|Builder|null
//    {
//        return $this->UnRegisteredAccountRepository->updateUnRegisteredAccount($id, $data);
//    }

    public function deleteUnRegisteredAccount($id): UnRegisteredAccount|Builder|null
    {
        return $this->UnRegisteredAccountRepository->deleteUnRegisteredAccount($id);
    }
}
