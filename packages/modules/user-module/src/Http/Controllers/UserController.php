<?php

namespace Modules\UserModule\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\HttpResponseService as HttpResponse;

use Modules\UserModule\Services\UserService;
use Modules\UserModule\Http\Requests\UpdateAccountDetailsRequest;

class UserController extends Controller
{
    public function index(Request $request)
    {
        try {
            $offset = $request->input('offset', 0);
            $limit = $request->input('limit', 10);

            $filters = json_decode($request->input('filters', []), true);

            $params = [];

            if (count($filters) > 0) {
                foreach($filters as $filter) {
                    $params[$filter['key']] = $filter['value'];
                }
            }

            $users = UserService::getAll($params, $offset, $limit, $request->input('requesting_by_admin'));

            return HttpResponse::success([
                'users' => $users
            ]);
        } catch (\Exception $e) {
            return HttpResponse::error($e->getMessage());
        }
    }

    public function show(Request $request)
    {
        try {
            $user = UserService::getOne([
                'id' => $request->input('id')
            ]);

            return HttpResponse::success([
                'user' => $user
            ]);
        } catch (\Exception $e) {
            return HttpResponse::error($e->getMessage());
        }
    }

    public function update(UpdateAccountDetailsRequest $request)
    {
        try {
            $user = UserService::update([
                'name' => $request->input('name'),
                'email' => $request->input('email'),
                'btc_wallet' => $request->input('btc_wallet')
            ], $request->input('id'));

            return HttpResponse::success([
                'user' => $user
            ]);
        } catch (\Exception $e) {
            return HttpResponse::error($e->getMessage());
        }
    }
}
