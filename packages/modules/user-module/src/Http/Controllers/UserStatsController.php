<?php

namespace Modules\UserModule\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\HttpResponseService as HttpResponse;

use Modules\UserModule\Services\UserService;

class UserStatsController extends Controller
{
    public function index(Request $request)
    {
        try {
            $totalCount = UserService::count([]);
            $totalActivated = UserService::count([
                'status' => 'activated'
            ]);
            $totalDeactivated = UserService::count([
                'status' => 'deactivated'
            ]);

            return HttpResponse::success([
                'total_count' => $totalCount,
                'total_activated' => $totalActivated,
                'total_deactivated' => $totalDeactivated,
            ]);
        } catch (\Exception $e) {
            return HttpResponse::error($e->getMessage());
        }
    }
}
