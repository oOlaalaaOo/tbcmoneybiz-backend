<?php

namespace Modules\UserModule\Services;

use App\User;

class UserDetailService
{
    private static $modelRelationships = [
        'orders'
    ];

    public static function getAll($params = [], $offset = 0, $limit = 10, $order_by = 'id', $order_type = 'desc')
    {
        $users = User::with(self::$modelRelationships)
                    ->when(isset($params['user_id']), function ($query) use ($params) {
                        return $query->where('user_id', $params['user_id']);
                    })
                    ->when(isset($params['name']), function ($query) use ($params) {
                        return $query->where('name', 'LIKE', '%' . $params['name'] . '%');
                    })
                    ->when(isset($params['email']), function ($query) use ($params) {
                        return $query->where('email', 'LIKE', '%' . $params['email'] . '%');
                    })
                    ->offset($offset)
                    ->limit($limit)
                    ->orderBy($order_by, $order_type)
                    ->get();

        \Log::info('user:get-all ' . \json_encode($users));

        return [
            'data'      => $users,
            'offset'    => $offset,
            'limit'     => $limit 
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

    public static function create($params = [])
    {
        if (!isset($params['name'])) {
            throw new \Exception('name is not specified', 1);
        }

        if (!isset($params['email'])) {
            throw new \Exception('email is not specified', 1);
        }

        if (!isset($params['password'])) {
            throw new \Exception('password is not specified', 1);
        }

        $user = new User;

        $user->name         = $params['name'];
        $user->email        = $params['email'];
        $user->password     = Hash::make($params['password']);

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
