<?php

namespace App\Infrastructure\Persistence\Eloquent;

use App\Domain\Models\User;
use App\Domain\Repositories\AuthenticationRepositoryInterface;
use App\Exceptions\EntryNotFoundException;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class EloquentAuthenticationRepository implements AuthenticationRepositoryInterface
{

    /**
     * @throws Exception
     */
    public function userLogin(array $credentials): array
    {
        $user = User::query()
            ->whereIn('user_type_id', [1,2])
            ->where(function (Builder $query) use ($credentials) {
                $query->where('email', optional($credentials)['email'])
                    ->orWhere('username', optional($credentials)['username']);
            })->first();

        if (!$user) {
            throw new EntryNotFoundException('المستخدم غير موجود', 404);
        }

        if (!Hash::check($credentials['password'], $user->password)) {
            throw new EntryNotFoundException("كلمة المرور غير صحيحة", 401);
        }

        if($user->user_type_id == 1) {
            return [
                'token' => $user->createToken('employee_auth_token')->plainTextToken,
                'user_type' => $user->user_type_id,
                'employee_name' => $user->employee->full_name,
            ];
        }
        else {
            return [
                'token' => $user->createToken('consultant_auth_token')->plainTextToken,
                'user_type' => $user->user_type_id,
                'consultant_name' => $user->consultant->first_name . ' ' . $user->consultant->last_name,
            ];
        }

    }

    /**
     * @throws EntryNotFoundException
     */
    public function userLogout(): void
    {
        // get the current authenticated user
        $user = Auth::user();


        // check if the user is authenticated
        if (!$user) {
            throw new EntryNotFoundException('المستخدم غير موجود', 404);
        }

        // revoke the token that was used to authenticate the current request
        $user->currentAccessToken()->delete();

    }

    /**
     * @throws EntryNotFoundException
     */
    public function getUserActivePermissionsByToken(): Collection
    {
        $user = Auth::user();

        if (!$user) {
            throw new EntryNotFoundException('المستخدم غير موجود', 404);
        }

        if ($user->user_type_id != 1) {
            throw new EntryNotFoundException('المستخدم غير موجود', 404);
        }

        $user_all_permissions = $user->employee->permissions;

        // get the list of employee permissions and filter the active ones
        $permission = $user_all_permissions->filter(function ($permission) use ($user_all_permissions) {
            // check if permission type = granted
            // type = default & it's not repeated with type = excluded

            $granted = $permission->type == 'granted';
            $default = $permission->type == 'default'
                && !$user_all_permissions->filter(function ($permission) {
                    return $permission->type == 'excluded';
                })->contains('perm_id', $permission->perm_id);

            return $granted || $default;
        });

        // extract only id & name
        $permission = $permission->map(function ($permission) {
            return [
                'perm_id' => $permission->perm_id,
                'name' => $permission->name,
            ];
        })->values();

        return collect($permission);
    }
}
