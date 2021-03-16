<?php

namespace Modules\MembershipModule\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\HttpResponseService as HttpResponse;

use Modules\MembershipModule\Services\MembershipService;
use Modules\MembershipModule\Http\Requests\PayRequest;

class PaidMembershipController extends Controller
{
    public function markAsPaid(Request $request)
    {
        try {
            $membership = MembershipService::update(
                [
                    'is_paid' => 1,
                    'paid_status' => 'confirmed',
                ],
                $request->input('membership_id'),
            );

            return HttpResponse::success([
                'membership' => $membership
            ]);
        } catch (\Exception $e) {
            return HttpResponse::error($e->getMessage());
        }
    }

    public function markAsUnPaid(Request $request)
    {
        try {
            $membership = MembershipService::update(
                [
                    'is_paid' => 0,
                    'paid_status' => 'pending',
                ],
                $request->input('membership_id'),
            );

            return HttpResponse::success([
                'membership' => $membership
            ]);
        } catch (\Exception $e) {
            return HttpResponse::error($e->getMessage());
        }
    }

    public function pay(PayRequest $request)
    {
        try {
            $membership = MembershipService::update(
                [
                    'paid_status' => 'pending',
                    'transaction_hash' => $request->input('transaction_hash'),
                    'btc_value' => $request->input('btc_value')
                ],
                $request->input('membership_id')
            );

            return HttpResponse::success([
                'membership' => $membership
            ]);
        } catch (\Exception $e) {
            return HttpResponse::error($e->getMessage());
        }
    }
}
