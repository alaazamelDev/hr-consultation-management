<?php

namespace App\Application\Http\Controllers;

use App\Application\Http\Requests\UserLoginRequest;
use App\Domain\Services\AuthenticationService;
use Illuminate\Http\JsonResponse;

class AuthenticationController extends Controller
{
    private AuthenticationService $AuthenticationService;

    public function __construct(AuthenticationService $AuthenticationService)
    {
        $this->AuthenticationService = $AuthenticationService;
    }

    // Employee Login route
    public function userLogin(UserLoginRequest $request): JsonResponse
    {
        $credentials = $request->only(['email', 'username', 'password']);
        $data = $this->AuthenticationService->userLogin($credentials);
        return response()->json($data);
    }

    // Employee Logout route
    public function userLogout(): JsonResponse
    {
        $this->AuthenticationService->userLogout();
        return response()->json([
            'message' => 'تم تسجيل الخروج بنجاح',
        ]);
    }

    // Retrieve Employee by token route
    public function getUserActivePermissionsByToken(): JsonResponse
    {
        $activePermissions = $this->AuthenticationService->getUserActivePermissionsByToken();
        return response()->json([
            'message' => 'تم استرجاع الصلاحيات بنجاح',
            'active_permissions' => $activePermissions,
        ]);
    }

}
