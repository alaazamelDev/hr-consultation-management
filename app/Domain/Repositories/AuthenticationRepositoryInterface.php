<?php

namespace App\Domain\Repositories;

use Illuminate\Support\Collection;

interface AuthenticationRepositoryInterface
{
    public function userLogin(array $credentials): array;

    public function userLogout(): void;

    public function getUserActivePermissionsByToken(): Collection;

}
