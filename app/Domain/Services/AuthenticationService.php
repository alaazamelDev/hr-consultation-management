<?php

namespace App\Domain\Services;

use App\Domain\Repositories\AuthenticationRepositoryInterface;
use Illuminate\Support\Collection;

class AuthenticationService
{
    private AuthenticationRepositoryInterface $AuthenticationRepository;

    public function __construct(AuthenticationRepositoryInterface $AuthenticationRepository)
    {
        $this->AuthenticationRepository = $AuthenticationRepository;
    }

    public function userLogin(array $credentials): array
    {
        return $this->AuthenticationRepository->userLogin($credentials);
    }

    public function userLogout(): void
    {
        $this->AuthenticationRepository->userLogout();
    }

    public function getUserActivePermissionsByToken(): Collection
    {
        return $this->AuthenticationRepository->getUserActivePermissionsByToken();
    }
}
