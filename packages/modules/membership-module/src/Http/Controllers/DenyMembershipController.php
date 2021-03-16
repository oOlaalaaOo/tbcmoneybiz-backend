<?php

namespace Modules\MembershipModule\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\HttpResponseService as HttpResponse;

use Modules\MembershipModule\Services\MembershipService;

class DenyMembershipController extends Controller
{
    public function deny(Request $request)
    {
        try {
            $membership = MembershipService::deny($request->input('membership_id'));

            return HttpResponse::success([
                'membership' => $membership
            ]);
        } catch (\Exception $e) {
            return HttpResponse::error($e->getMessage());
        }
    }
}
