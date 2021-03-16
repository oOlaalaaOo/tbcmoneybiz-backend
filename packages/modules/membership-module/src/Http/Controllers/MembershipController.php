<?php

namespace Modules\MembershipModule\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\HttpResponseService as HttpResponse;

use Modules\MembershipModule\Services\MembershipService;

class MembershipController extends Controller
{
    public function index(Request $request)
    {
        try {
            $offset = $request->input('offset', 0);
            $limit = $request->input('limit', 10);
            $filters = $request->has('filters') ? json_decode($request->input('filters', []), true) : [];

            $params = [];

            if (count($filters) > 0) {
                foreach($filters as $filter) {
                    $params[$filter['key']] = $filter['value'];
                }
            }

            $memberships = MembershipService::getAll($params, $offset, $limit);

            return HttpResponse::success([
                'memberships' => $memberships
            ]);
        } catch (\Exception $e) {
            return HttpResponse::error($e->getMessage());
        }
    }
}
