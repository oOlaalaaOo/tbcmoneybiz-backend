<?php

namespace Modules\AuthModule\Services;

use App\User;
use Modules\MembershipModule\Services\MembershipService;
use Auth;
use Hash;

class AuthService
{
    public static function loginUser($credentials = [])
    {
        if (!isset($credentials['email'])) {
            throw new \Exception('email is not specified', 1);
        }

        if (!isset($credentials['password'])) {
            throw new \Exception('password is not specified', 1);
        }

        if (Auth::attempt([
                'email' => $credentials['email'],
                'password' => $credentials['password'],
                'is_admin' => 0
            ])) {
            return self::createUserToken();
        }

        return null;
    }

    public static function loginAdmin($credentials = [])
    {
        if (!isset($credentials['email'])) {
            throw new \Exception('email is not specified', 1);
        }

        if (!isset($credentials['password'])) {
            throw new \Exception('password is not specified', 1);
        }

        if (Auth::attempt([
                'email' => $credentials['email'],
                'password' => $credentials['password'],
                'is_admin' => 1
            ])) {
            return self::createAdminUserToken();
        }

        return null;
    }

    private static function createUserToken()
    {
        $user = Auth::user();

        $memberships = MembershipService::getAll(['user_id' => $user->id]);

        $formattedUser = [
            'id' => $user->id,
            'btc_wallet' => $user->btc_wallet,
            'email' => $user->email,
            'name' => $user->name,
            'memberships' => $memberships['data']
        ];

        $token = $user->createToken('project_30', ['user'])->accessToken;

        return [
            'user' => $formattedUser,
            'accessToken' => $token
        ];
    }

    private static function createAdminUserToken()
    {
        $user = Auth::user();

        $formattedUser = [
            'id' => $user->id,
            'btc_wallet' => $user->btc_wallet,
            'email' => $user->email,
            'name' => $user->name
        ];

        $token = $user->createToken('project_30', ['admin'])->accessToken;

        return [
            'user' => $formattedUser,
            'accessToken' => $token
        ];
    }
}
