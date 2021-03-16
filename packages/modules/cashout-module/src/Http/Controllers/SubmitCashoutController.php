<?php

namespace Modules\CashoutModule\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\HttpResponseService as HttpResponse;

use Modules\CashoutModule\Services\CashoutService;
use Modules\CashoutModule\Http\Requests\SubmitCashoutReqeust;

class SubmitCashoutController extends Controller
{
    public function cashout(SubmitCashoutReqeust $request)
    {
        try {
            $cashout = CashoutService::create([
                'membership_id' => $request->input('membership_id'),
                'user_id' => $request->input('user_id'),
                'unilevel_points' => $request->input('unilevel_points'),
                'interest' => $request->input('interest'),
                'status' => 'pending',
                'confirmed_at' => null,
                'denied_at' => null,
                'usd_value' => 0,
                'btc_value' => 0,
                'referral_points' => 0,
                'transaction_hash' => uniqid(),
            ]);

            return HttpResponse::success([
                'cashout' => $cashout
            ]);
        } catch (\Exception $e) {
            return HttpResponse::error($e->getMessage());
        }
    }
}
