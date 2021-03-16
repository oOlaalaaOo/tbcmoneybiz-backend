<?php

namespace Modules\CashoutModule\Services;

use Modules\CashoutModule\Models\Cashout;
use Modules\MembershipModule\Services\MembershipService;
use Hash;
use App\User;

class CashoutService
{
    private static $modelRelationships = [
        'user',
        'membership.plan'
    ];

    public static function getAll($params = [], $offset = 0, $limit = 10, $order_by = 'id', $order_type = 'desc')
    {
        $cashouts = Cashout::with(self::$modelRelationships)
                    ->when(isset($params['user_id']), function ($query) use ($params) {
                        return $query->where('user_id', $params['user_id']);
                    })
                    ->when(isset($params['transaction_hash']), function ($query) use ($params) {
                        return $query->where('transaction_hash', $params['transaction_hash']);
                    })
                    ->when(isset($params['name']), function ($query) use ($params) {
                        return $query->where('name', 'LIKE', '%' . $params['name'] . '%');
                    })
                    ->when(isset($params['status']), function ($query) use ($params) {
                        if ($params['status'] != 'all') {
                            return $query->where('status', $params['status']);
                        }
                    });
        

        $totalCount = $cashouts->count();

        $cashouts = $cashouts->offset($offset)
                    ->limit($limit)
                    ->orderBy($order_by, $order_type)
                    ->get();

        \Log::info('cashout:get-all ' . \json_encode($cashouts));

        return [
            'data'          => $cashouts,
            'offset'        => $offset,
            'limit'         => $limit,
            'total_count'   => $totalCount
        ];
    }

    public static function getOne($params = [], $order_by = 'id', $order_type = 'asc')
    {
        $cashout = Cashout::with(self::$modelRelationships)
                    ->when(isset($params['id']), function ($query) use ($params) {
                        return $query->where('id', $params['id']);
                    })
                    ->when(isset($params['membership_id']), function ($query) use ($params) {
                        return $query->where('membership_id', $params['membership_id']);
                    })
                    ->when(isset($params['user_id']), function ($query) use ($params) {
                        return $query->where('user_id', $params['user_id']);
                    })
                    ->when(isset($params['created_at']), function ($query) use ($params) {
                        return $query->whereDate('created_at', $params['created_at']);
                    })
                    ->orderBy($order_by, $order_type)
                    ->first();

        \Log::info('cashout:get-one ' . json_encode($cashout));

        return $cashout;
    }

    public static function create($params = [])
    {
        if (!isset($params['user_id'])) {
            throw new \Exception('user_id is not specified', 1);
        }

        if (!isset($params['membership_id'])) {
            throw new \Exception('membership_id is not specified', 1);
        }

        if (!isset($params['status'])) {
            throw new \Exception('status is not specified', 1);
        }

        $cashout = new Cashout;

        $cashout->user_id = $params['user_id'];
        $cashout->membership_id = $params['membership_id'];
        $cashout->transaction_hash = $params['transaction_hash'];
        $cashout->usd_value = $params['usd_value'];
        $cashout->btc_value = $params['btc_value'];
        $cashout->referral_points = $params['referral_points'];
        $cashout->accepted_referral_points = $params['referral_points'];
        $cashout->unilevel_points = $params['unilevel_points'];
        $cashout->accepted_unilevel_points = $params['unilevel_points'];
        $cashout->interest = $params['interest'];
        $cashout->accepted_interest = $params['interest'];
        $cashout->status = $params['status'];
        $cashout->confirmed_at = $params['confirmed_at'];
        $cashout->denied_at = $params['denied_at'];

        if (!$cashout->save()) {
            throw new \Exception('error in saving cashout', 1);
        }

        \Log::info('cashout:created ' . \json_encode($cashout));

        return $cashout;
    }

    public static function update($params = [], $id)
    {
        $cashout = Cashout::find($id);

        if (isset($params['transaction_hash'])) {
            $cashout->transaction_hash = $params['transaction_hash'];
        }

        if (isset($params['usd_value'])) {
            $cashout->usd_value = $params['usd_value'];
        }

        if (isset($params['btc_value'])) {
            $cashout->btc_value = $params['btc_value'];
        }

        if (isset($params['referral_points'])) {
            $cashout->referral_points = $params['referral_points'];
        }

        if (isset($params['interest'])) {
            $cashout->interest = $params['interest'];
        }

        if (isset($params['unilevel_points'])) {
            $cashout->unilevel_points = $params['unilevel_points'];
        }

        if (isset($params['status'])) {
            $cashout->status = $params['status'];
        }

        if (isset($params['confirmed_at'])) {
            $cashout->confirmed_at = $params['confirmed_at'];
        }

        if (isset($params['denied_at'])) {
            $cashout->denied_at = $params['denied_at'];
        }

        if (!$cashout->save()) {
            throw new \Exception('error in saving cashout', 1);
        }

        \Log::info('cashout:updated ' . \json_encode($cashout));

        return $cashout;
    }

    public static function delete($id = null)
    {
        if (!$id) {
            throw new \Exception('id is not specified', 1);
        }

        $cashout = Cashout::where('id', $id)->delete();

        if (!$cashout) {
            throw new \Exception('error in deleting cashout', 1);
        }

        \Log::info('cashout:deleted ' . json_encode($cashout));

        return $cashout;
    }

    public static function confirm($id, $transaction_hash, $accepted_interest, $accepted_unilevel_points)
    {
        $cashout = Cashout::with(self::$modelRelationships)
                ->where('id', $id)
                ->first();

        $now = date('Y-m-d H:i:s');

        $user = User::where('id', $cashout->user_id)->first();

        $user->last_cashout_at = $now;
        $user->save();

        $cashout->status = 'confirmed';
        $cashout->confirmed_at = $now;
        $cashout->transaction_hash = $transaction_hash;
        $cashout->accepted_interest = $accepted_interest;
        $cashout->accepted_unilevel_points = $accepted_unilevel_points;
        $cashout->save();

        $membership = MembershipService::getOne([
            'id' => $cashout->membership_id
        ]);

        MembershipService::update([
            'interest' => $membership->interest - $accepted_interest,
            'unilevel_points' => $membership->unilevel_points - $accepted_unilevel_points,
        ], $cashout->membership_id);

        return $cashout;
    }

    public static function deny($id)
    {
        $cashout = Cashout::with(self::$modelRelationships)
                ->where('id', $id)
                ->first();

        $now = date('Y-m-d H:i:s');

        $user = User::where('id', $cashout->user_id)->first();

        $user->last_cashout_at = $now;
        $user->save();

        $cashout->status = 'denied';
        $cashout->denied_at = $now;
        $cashout->save();

        return $cashout;
    }
}
