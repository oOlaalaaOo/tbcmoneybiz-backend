<?php

namespace Modules\UserModule\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\HttpResponseService as HttpResponse;

use Modules\UserModule\Services\UserService;

class ValidateEmailController extends Controller
{
    public function checkEmailIfExists(Request $request)
    {
        try {
            $count = UserService::count([
                'email' => $request->input('email')
            ]);

            return HttpResponse::success([
                'valid' => (bool) $count > 0 ? true : false
            ]);
        } catch (\Exception $e) {
            return HttpResponse::error($e->getMessage());
        }
    }
}
