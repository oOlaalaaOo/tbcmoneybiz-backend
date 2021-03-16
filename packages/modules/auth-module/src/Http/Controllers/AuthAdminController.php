<?php

namespace Modules\AuthModule\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\HttpResponseService as HttpResponse;

use Modules\AuthModule\Http\Requests\RegisterRequest;
use Modules\AuthModule\Http\Requests\LoginRequest;
use Modules\AuthModule\Services\AuthService;
use Modules\UserModule\Services\UserService;
use Modules\MembershipModule\Services\MembershipService;
use Modules\PlanModule\Services\PlanService;

class AuthAdminController extends Controller
{
    public function login(LoginRequest $request, AuthService $authService)
    {
        try {
            $authUser = $authService->loginAdmin($request->only(['email', 'password']));

            if (!$authUser) {
                return HttpResponse::error('The username and password did not matched.', 403);
            }

            return HttpResponse::success($authUser);
        } catch (\Exception $e) {
            return HttpResponse::error($e->getMessage());
        }
    }
}
