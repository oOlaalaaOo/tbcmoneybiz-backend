<?php

namespace Modules\MembershipModule\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\HttpResponseService as HttpResponse;

use Modules\MembershipModule\Services\MembershipService;

class ConfirmMembershipController extends Controller
{
    public function confirm(Request $request)
    {
        try {
            $membership = MembershipService::confirm(
                $request->input('membership_id'),
                $request->input('mark_as_paid')
            );

            return HttpResponse::success([
                'membership' => $membership
            ]);
        } catch (\Exception $e) {
            return HttpResponse::error($e->getMessage());
        }
    }
}
