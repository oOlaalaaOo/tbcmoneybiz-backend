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

class AuthController extends Controller
{
    public function login(LoginRequest $request, AuthService $authService)
    {
        try {
            $authUser = $authService->loginUser($request->only(['email', 'password']));

            if (!$authUser) {
                return HttpResponse::error('The username and password did not matched.', 403);
            }

            return HttpResponse::success($authUser);
        } catch (\Exception $e) {
            return HttpResponse::error($e->getMessage());
        }
    }

    public function register(RegisterRequest $request)
    {
        try {
            $isReferralIdExists = MembershipService::count([
                'referral_link' => $request->input('referral_id'),
                'status' => 'confirmed'
            ]);

            if (!$isReferralIdExists) {
                return response()->json([
                    'referral_id' => 'is not confirmed',
                    'isReferralIdExists' => $isReferralIdExists
                ], 500);
            }

            $user = UserService::create($request->all());

            if ($user) {
                $plan = PlanService::getOne('level-1');

                $membership = MembershipService::create([
                    'plan_id' => $plan->id,
                    'user_id' => $user->id,
                    'referral_link' => $this->generateUniqueCode(),
                    'referral_id' => $request->input('referral_id'),
                    'unilevel_points' => 0,
                    'referral_points' => 0,
                    // 'transaction_hash' => $request->input('transaction_hash'),
                    // 'current_btc_value' => $request->input('btc_value'),
                    'transaction_hash' => uniqid(),
                    'current_btc_value' => 0,
                    'admin_btc_wallet' => $request->input('admin_btc_wallet'),
                    'status' => 'pending',
                ]);

                // MembershipService::confirm(
                //     $membership->id,
                //     false
                // );

                return HttpResponse::success([
                    'user' => $user,
                    'membership' => $membership
                ]);
            }

            return HttpResponse::error('Error: failed to save user');
        } catch (\Exception $e) {
            return HttpResponse::error($e->getMessage());
        }
    }

    private function generateUniqueCode()
    {
        return uniqid();
    }
}
