<?php


namespace App\Infrastructure\Persistence\Eloquent;


use App\Domain\Models\User;
use App\Domain\Repositories\UserRepositoryInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;

class EloquentUserRepository implements UserRepositoryInterface
{

    public function getUserList(): Collection
    {
        return User::query()->get();
    }

    public function createUser(array $data): Builder|Model
    {
        // create user
        return User::query()->create([
            'user_type_id' => $data['user_type_id'], // 1 = 'employee', 2 = 'consultant'
            'email' => $data['email'],
            'username' => $data['username'],
            'password' => Hash::make($data['password']),
        ]);
    }
}
