<?php

namespace Modules\CashoutModule\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\HttpResponseService as HttpResponse;

use Modules\CashoutModule\Services\CashoutService;

class ConfirmCashoutController extends Controller
{
    public function confirm(Request $request)
    {
        try {
            $cashout = CashoutService::confirm(
                $request->input('cashout_id'),
                $request->input('transaction_hash'),
                $request->input('accepted_interest'),
                $request->input('accepted_unilevel_points'),
            );

            return HttpResponse::success([
                'cashout' => $cashout
            ]);
        } catch (\Exception $e) {
            return HttpResponse::error($e->getMessage());
        }
    }
}
