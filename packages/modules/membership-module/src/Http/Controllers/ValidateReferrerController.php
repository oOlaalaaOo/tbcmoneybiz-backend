<?php

namespace Modules\MembershipModule\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\HttpResponseService as HttpResponse;

use Modules\MembershipModule\Services\MembershipService;

class ValidateReferrerController extends Controller
{
    public function checkReferrerIdExists(Request $request)
    {
        try {
            $referralLink = $request->input('referral_link');
            $count = 0;

            if ($referralLink == 'leader') {
                $count = 1;
            } else {
                $count = MembershipService::count([
                    'referral_link' => $request->input('referral_link'),
                    'status' => 'confirmed'
                ]);
            }

            return HttpResponse::success([
                'valid' => (bool) $count > 0 ? true : false
            ]);
        } catch (\Exception $e) {
            return HttpResponse::error($e->getMessage());
        }
    }
}
