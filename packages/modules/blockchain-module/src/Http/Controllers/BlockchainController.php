<?php

namespace Modules\BlockchainModule\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\HttpResponseService as HttpResponse;

use Modules\BlockchainModule\Services\BlockchainService;

class BlockchainController extends Controller
{
    public function getBtcValueFromUsd(Request $request)
    {
        try {
            $btcValue = BlockchainService::convertUsdToBtc($request->input('usd_amount'));

            return HttpResponse::success([
                'btc_value' => $btcValue
            ]);
        } catch (\Exception $e) {
            return HttpResponse::error($e->getMessage());
        }
    }
}
