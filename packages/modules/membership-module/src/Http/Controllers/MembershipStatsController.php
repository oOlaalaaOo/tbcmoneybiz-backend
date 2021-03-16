<?php

namespace Modules\MembershipModule\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\HttpResponseService as HttpResponse;

use Modules\MembershipModule\Services\MembershipService;

class MembershipStatsController extends Controller
{
    public function index(Request $request)
    {
        try {
            $totalCount = MembershipService::count([]);
            $totalPending = MembershipService::count([
                'status' => 'pending'
            ]);
            $totalConfirmed = MembershipService::count([
                'status' => 'confirmed'
            ]);
            $totalDenied = MembershipService::count([
                'status' => 'denied'
            ]);
            $totalCurrentUsdValue = MembershipService::currentUsdValueSum([]);
            $totalCurrentBtcValue = MembershipService::currentBtcValueSum([]);

            return HttpResponse::success([
                'total_count' => $totalCount,
                'total_pending' => $totalPending,
                'total_confirmed' => $totalConfirmed,
                'total_denied' => $totalDenied,
                'total_current_usd_value' => $totalCurrentUsdValue,
                'total_current_btc_value' => $totalCurrentBtcValue,
            ]);
        } catch (\Exception $e) {
            return HttpResponse::error($e->getMessage());
        }
    }

    public function getUserMembershipDetails(Request $request)
    {
        try {
            $userId = $request->input('user_id');

            $membership = MembershipService::getOne([
                'user_id' => $userId
            ]);

            return HttpResponse::success([
                'membership' => $membership
            ]);
        } catch (\Exception $e) {
            return HttpResponse::error($e->getMessage());
        }
    }

    public function getUserMembershipDownline(Request $request)
    {
        try {
            $userId = $request->input('user_id');
            $level = $request->input('level');

            $downlines = MembershipService::downlines($userId, $level);

            return HttpResponse::success([
                'downlines' => $downlines,
                'level' => $level
            ]);
        } catch (\Exception $e) {
            return HttpResponse::error($e->getMessage());
        }
    }
}
