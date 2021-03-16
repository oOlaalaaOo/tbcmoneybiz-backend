<?php

namespace Modules\MembershipModule\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\HttpResponseService as HttpResponse;

use Modules\MembershipModule\Services\MembershipService;

class ValidateTransactionHashController extends Controller
{
    public function checkTransactionHashIfExists(Request $request)
    {
        try {
            $count = MembershipService::count([
                'transaction_hash' => $request->input('transaction_hash')
            ]);

            return HttpResponse::success([
                'valid' => (bool) $count > 0 ? true : false
            ]);
        } catch (\Exception $e) {
            return HttpResponse::error($e->getMessage());
        }
    }
}
