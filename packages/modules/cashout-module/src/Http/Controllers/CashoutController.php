<?php

namespace Modules\CashoutModule\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\HttpResponseService as HttpResponse;

use Modules\CashoutModule\Services\CashoutService;
use Carbon\Carbon;

class CashoutController extends Controller
{
    public function getAll(Request $request)
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

            $cashouts = CashoutService::getAll($params, $offset, $limit);

            return HttpResponse::success([
                'cashouts' => $cashouts
            ]);
        } catch (\Exception $e) {
            return HttpResponse::error($e->getMessage());
        }
    }

    public function userAlreadyCashout(Request $request)
    {
        try {
            $today = date('Y-m-d');

            $params = [
                'user_id' => $request->input('user_id'),
                'created_at' => $today
            ];

            $isToday = false;

            $cashout = CashoutService::getOne($params, 'id', 'desc');


            if ($cashout) {
                $createdAt = Carbon::parse($cashout->created_at);

                if ($createdAt->toDateString() == $today) {
                    $isToday = true;
                }
            }

            return HttpResponse::success([
                'cashout' => $cashout,
                'isToday' => $isToday,
                'today' => $today,
            ]);
        } catch (\Exception $e) {
            return HttpResponse::error($e->getMessage());
        }
    }
}
