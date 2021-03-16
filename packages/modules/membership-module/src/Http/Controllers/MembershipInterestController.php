<?php

namespace Modules\MembershipModule\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\HttpResponseService as HttpResponse;

use Modules\MembershipModule\Services\MembershipService;

class MembershipInterestController extends Controller
{
    public function addInterest(Request $request)
    {
        try {
            $membership = MembershipService::addInterest($request->input('membership_id'));

            return HttpResponse::success([
                'membership' => $membership
            ]);
        } catch (\Exception $e) {
            return HttpResponse::error($e->getMessage());
        }
    }
}
