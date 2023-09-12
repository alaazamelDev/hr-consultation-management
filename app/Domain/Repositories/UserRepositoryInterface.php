<?php


namespace App\Domain\Repositories;


use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

interface UserRepositoryInterface
{
    public function getUserList(): Collection;

    public function createUser(array $data): Builder|Model;

}
