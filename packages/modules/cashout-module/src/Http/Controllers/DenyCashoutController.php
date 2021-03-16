<?php

namespace Modules\CashoutModule\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\HttpResponseService as HttpResponse;

use Modules\CashoutModule\Services\CashoutService;

class DenyCashoutController extends Controller
{
    public function deny(Request $request)
    {
        try {
            $cashout = CashoutService::deny(
                $request->input('cashout_id')
            );

            return HttpResponse::success([
                'cashout' => $cashout
            ]);
        } catch (\Exception $e) {
            return HttpResponse::error($e->getMessage());
        }
    }
}
