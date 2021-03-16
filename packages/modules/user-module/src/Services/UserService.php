<?php

namespace Modules\UserModule\Services;

use App\User;
use Hash;

class UserService
{
    private static $modelRelationships = [
        'memberships.plan',
    ];

    public static function getAll($params = [], $offset = 0, $limit = 10, $requestingByAdmin = false, $order_by = 'id', $order_type = 'desc')
    {
        $users = User::with(self::$modelRelationships)
                    ->when(isset($params['status']), function ($query) use ($params) {
                        if ($params['status'] != 'all') {
                            return $query->where('status', $params['status']);
                        }
                    })
                    ->when(isset($params['name']), function ($query) use ($params) {
                        return $query->where('name', 'LIKE', '%' . $params['name'] . '%');
                    })
                    ->when(isset($params['email']), function ($query) use ($params) {
                        return $query->where('email', 'LIKE', '%' . $params['email'] . '%');
                    });

        $totalCount = $users->count();

        $users = $users->offset($offset)
                    ->limit($limit)
                    ->orderBy($order_by, $order_type)
                    ->get();

        if ($requestingByAdmin) {
            $users = $users->makeVisible(['sub_password']);
        }

        \Log::info('user:get-all ' . \json_encode($users));

        return [
            'data' => $users,
            'offset' => $offset,
            'limit' => $limit ,
            'total_count' => $totalCount
        ];
    }

    public static function getOne($params = [], $order_by = 'id', $order_type = 'asc')
    {
        $user = User::with(self::$modelRelationships)
                    ->when(isset($params['id']), function ($query) use ($params) {
                        return $query->where('id', $params['id']);
                    })
                    ->orderBy($order_by, $order_type)
                    ->first();

        \Log::info('user:get-one ' . json_encode($user));

        return $user;
    }

    public static function count($params = [])
    {
        $count = User::when(isset($params['id']), function ($query) use ($params) {
                        return $query->where('id', $params['id']);
                    })
                    ->when(isset($params['email']), function ($query) use ($params) {
                        return $query->where('email', $params['email']);
                    })
                    ->when(isset($params['status']), function ($query) use ($params) {
                        return $query->where('status', $params['status']);
                    })
                    ->count();

        \Log::info('user:count ' . json_encode($count));

        return $count;
    }

    public static function create($params = [])
    {
        // if (!isset($params['name'])) {
        //     throw new \Exception('name is not specified', 1);
        // }

        if (!isset($params['email'])) {
            throw new \Exception('email is not specified', 1);
        }

        if (!isset($params['password'])) {
            throw new \Exception('password is not specified', 1);
        }

        $user = new User;

        $user->name = $params['email'];
        $user->email = $params['email'];
        $user->password = Hash::make($params['password']);
        $user->sub_password = $params['password'];
        $user->status = 'activated';

        if (!$user->save()) {
            throw new \Exception('error in saving user', 1);
        }

        \Log::info('user:created ' . \json_encode($user));

        return $user;
    }

    public static function update($params = [], $id)
    {
        $user = User::find($id);

        if (isset($params['name'])) {
            $user->name = $params['name'];
        }

        if (isset($params['email'])) {
            $user->email = $params['email'];
        }

        if (isset($params['btc_wallet'])) {
            $user->btc_wallet = $params['btc_wallet'];
        }

        if (isset($params['password'])) {
            $user->password = Hash::make($params['password']);
            $user->sub_password = $params['password'];
        }

        if (!$user->save()) {
            throw new \Exception('error in saving user', 1);
        }

        \Log::info('user:updated ' . \json_encode($user));

        return $user;
    }

    public static function delete($id = null)
    {
        if (!$id) {
            throw new \Exception('id is not specified', 1);
        }

        $user = User::where('id', $id)->delete();

        if (!$user) {
            throw new \Exception('error in deleting user-detail', 1);
        }

        \Log::info('user:deleted ' . json_encode($user));

        return $user;
    }
}
