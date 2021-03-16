<?php

namespace Modules\UserModule\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\HttpResponseService as HttpResponse;

use Modules\UserModule\Services\UserService;
use Hash;

class ChangePasswordController extends Controller
{
    public function update(Request $request)
    {
        try {
            $user = UserService::getOne([
                'id' => $request->input('user_id')
            ]);

            $currentPassword = $user->password;
            $newPassword = $request->input('new_password');
            $oldPassword = $request->input('old_password');

            $updatedUser = null;

            if (Hash::check($oldPassword, $currentPassword)) {
                $updatedUser = UserService::update([
                    'password' => $newPassword
                ]);
            } else {
                return HttpResponse::error('Old password is not correct', 401);
            }

            return HttpResponse::success([
                'user' => $updatedUser
            ]);
        } catch (\Exception $e) {
            return HttpResponse::error($e->getMessage());
        }
    }
}
