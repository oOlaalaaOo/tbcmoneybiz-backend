<?php

namespace Modules\MembershipModule\Services;

use Illuminate\Database\Eloquent\Builder;
use Modules\MembershipModule\Models\Membership;
use Modules\MembershipModule\Models\MembershipPointLog;
use App\User;

class MembershipService
{
    private static $modelRelationships = [
        'user',
        'plan'
    ];

    public static function getAll($params = [], $offset = 0, $limit = 10, $order_by = 'id', $order_type = 'desc')
    {
        $memberships = Membership::with(self::$modelRelationships)
                    ->when(isset($params['user_id']), function ($query) use ($params) {
                        return $query->where('user_id', $params['user_id']);
                    })
                    ->when(isset($params['plan_id']), function ($query) use ($params) {
                        return $query->where('plan_id', $params['plan_id']);
                    })
                    ->when(isset($params['status']), function ($query) use ($params) {
                        if ($params['status'] != 'all') {
                            return $query->where('status', $params['status']);
                        }
                    })
                    ->when(isset($params['paid_status']), function ($query) use ($params) {
                        if ($params['paid_status'] != 'all') {
                            return $query->where('paid_status', $params['paid_status']);
                        }
                    })
                    ->when(isset($params['transaction_hash']), function ($query) use ($params) {
                        return $query->where('transaction_hash', $params['transaction_hash']);
                    })
                    ->when(isset($params['referral_link']), function ($query) use ($params) {
                        return $query->where('referral_link', $params['referral_link']);
                    })
                    ->when(isset($params['member_name']), function ($query) use ($params) {
                        return $query->whereHas('user', function (Builder $q) use ($params) {
                            $q->where('name', 'like', '%' . $params['member_name'] . '%');
                        });
                    });

        $totalCount = $memberships->count();

        $memberships = $memberships->offset($offset)
                    ->limit($limit)
                    ->orderBy($order_by, $order_type)
                    ->get();

        \Log::info('membership:get-all ' . \json_encode($memberships));

        return [
            'data' => $memberships,
            'offset' => $offset,
            'limit' => $limit,
            'total_count' => $totalCount
        ];
    }

    public static function getOne($params = [], $order_by = 'id', $order_type = 'asc')
    {
        $membership = Membership::with(self::$modelRelationships)
                    ->when(isset($params['id']), function ($query) use ($params) {
                        return $query->where('id', $params['id']);
                    })
                    ->when(isset($params['user_id']), function ($query) use ($params) {
                        return $query->where('user_id', $params['user_id']);
                    })
                    ->when(isset($params['referral_link']), function ($query) use ($params) {
                        return $query->where('referral_link', $params['referral_link']);
                    })
                    ->orderBy($order_by, $order_type)
                    ->first();

        \Log::info('membership:get-one ' . json_encode($membership));

        return $membership;
    }

    public static function count($params = [])
    {
        $count = Membership::when(isset($params['id']), function ($query) use ($params) {
                        return $query->where('id', $params['id']);
                    })
                    ->when(isset($params['referral_link']), function ($query) use ($params) {
                        return $query->where('referral_link', $params['referral_link']);
                    })
                    ->when(isset($params['referral_id']), function ($query) use ($params) {
                        return $query->where('referral_id', $params['referral_id']);
                    })
                    ->when(isset($params['transaction_hash']), function ($query) use ($params) {
                        return $query->where('transaction_hash', $params['transaction_hash']);
                    })
                    ->when(isset($params['status']), function ($query) use ($params) {
                        return $query->where('status', $params['status']);
                    })
                    ->when(isset($params['paid_status']), function ($query) use ($params) {
                        return $query->where('paid_status', $params['paid_status']);
                    })
                    ->count();

        \Log::info('membership:count ' . json_encode($count));

        return $count;
    }

    public static function currentUsdValueSum($params = [])
    {
        $sum = Membership::with(self::$modelRelationships)
            ->when(isset($params['status']), function ($query) use ($params) {
                return $query->where('status', $params['status']);
            })
            ->sum('current_usd_value');

        \Log::info('membership:sum ' . json_encode($sum));

        return $sum;
    }

    public static function currentBtcValueSum($params = [])
    {
        $sum = Membership::with(self::$modelRelationships)
            ->when(isset($params['status']), function ($query) use ($params) {
                return $query->where('status', $params['status']);
            })
            ->sum('current_btc_value');

        \Log::info('membership:sum ' . json_encode($sum));

        return $sum;
    }

    public static function create($params = [])
    {
        if (!isset($params['plan_id'])) {
            throw new \Exception('plan_id is not specified', 1);
        }

        if (!isset($params['user_id'])) {
            throw new \Exception('user_id is not specified', 1);
        }

        if (!isset($params['referral_link'])) {
            throw new \Exception('referral_link is not specified', 1);
        }

        if (!isset($params['referral_id'])) {
            throw new \Exception('referral_id is not specified', 1);
        }

        if (!isset($params['unilevel_points'])) {
            throw new \Exception('unilevel_points is not specified', 1);
        }

        if (!isset($params['referral_points'])) {
            throw new \Exception('referral_points is not specified', 1);
        }

        if (!isset($params['transaction_hash'])) {
            throw new \Exception('transaction_hash is not specified', 1);
        }

        if (!isset($params['admin_btc_wallet'])) {
            throw new \Exception('admin_btc_wallet is not specified', 1);
        }

        if (!isset($params['current_btc_value'])) {
            throw new \Exception('current_btc_value is not specified', 1);
        }

        if (!isset($params['status'])) {
            throw new \Exception('status is not specified', 1);
        }

        $membership = new Membership;

        $membership->plan_id = $params['plan_id'];
        $membership->user_id = $params['user_id'];
        $membership->referral_link = $params['referral_link'];
        $membership->referral_id = $params['referral_id'];
        $membership->unilevel_points = $params['unilevel_points'];
        $membership->referral_points = $params['referral_points'];
        $membership->transaction_hash = $params['transaction_hash'];
        $membership->current_btc_value = $params['current_btc_value'];
        $membership->admin_btc_wallet = $params['admin_btc_wallet'];
        $membership->status = $params['status'];

        if (!$membership->save()) {
            throw new \Exception('error in saving membership', 1);
        }

        \Log::info('membership:created ' . \json_encode($membership));

        return $membership;
    }

    public static function update($params = [], $id)
    {
        $membership = Membership::find($id);

        if (isset($params['plan_id'])) {
            $membership->plan_id = $params['plan_id'];
        }

        if (isset($params['user_id'])) {
             $membership->user_id = $params['user_id'];
        }

        if (isset($params['plan_cost'])) {
            $membership->plan_cost = $params['plan_cost'];
        }

        if (isset($params['plan_points'])) {
            $membership->plan_points = $params['plan_points'];
        }

        if (isset($params['admin_btc_wallet'])) {
            $membership->admin_btc_wallet = $params['admin_btc_wallet'];
        }

        if (isset($params['status'])) {
            $membership->status = $params['status'];
        }

        if (isset($params['interest'])) {
            $membership->interest = $params['interest'];
        }

        if (isset($params['unilevel_points'])) {
            $membership->unilevel_points = $params['unilevel_points'];
        }

        if (isset($params['referral_points'])) {
            $membership->referral_points = $params['referral_points'];
        }

        if (isset($params['is_paid'])) {
            $membership->is_paid = $params['is_paid'];
        }

        if (isset($params['paid_status'])) {
            $membership->paid_status = $params['paid_status'];
        }

        if (!$membership->save()) {
            throw new \Exception('error in saving membership', 1);
        }

        \Log::info('membership:updated ' . \json_encode($membership));

        return $membership;
    }

    public static function delete($id = null)
    {
        if (!$id) {
            throw new \Exception('id is not specified', 1);
        }

        $membership = Membership::where('id', $id)->delete();

        if (!$membership) {
            throw new \Exception('error in deleting membership', 1);
        }

        \Log::info('membership:deleted ' . json_encode($membership));

        return $membership;
    }

    public static function confirm($id, $markAsPaid = false)
    {
        $membership = Membership::with(self::$modelRelationships)
                ->with(['plan', 'user'])
                ->where('id', $id)
                ->first();

        $now = date('Y-m-d H:i:s');

        $user = User::where('id', $membership->user_id)->first();

        $user->last_activated_at = $now;
        $user->status = 'activated';
        $user->save();

        $membership->status = 'confirmed';
        $membership->paid_status = $markAsPaid == true ? 'confirmed' : 'none';
        $membership->is_paid = $markAsPaid == true ? 1 : 0;
        $membership->confirmed_at = $now;
        $membership->save();

        self::getUplines($membership->user_id, $membership->referral_id, $membership->plan->cost);

        return $membership;
    }

    private static function getUplines($referredUserId, $referredReferralId, $cost)
    {
        $dynamicReferredReferralId = $referredReferralId;

        for ($i = 1; $i <= 20; $i++) {
            $pointsRate = self::costPointsRatePerLevel($i, $cost);

            $referrer = Membership::where('referral_link', $dynamicReferredReferralId)
                ->where('status', 'confirmed')
                ->first();

            if ($referrer) {
                $referrer->unilevel_points += $pointsRate;
                $referrer->save();

                self::logAddingUnilevelPoints(
                    $referredUserId,
                    $referrer->user_id,
                    $pointsRate,
                    $i
                );

                $dynamicReferredReferralId = $referrer->referral_id;
            } else {
                break;
            }
        }
    }

    private static function costPointsRatePerLevel($level, $cost)
    {
        $costPointsRate = 0;

        // if ($level == 1) {
        //     $costPointsRate = $cost * 0.1;
        // } elseif ($level >= 2 && $level <= 4) {
        //     $costPointsRate = $cost * 0.013;
        // } elseif ($level >= 5 && $level <= 10) {
        //     $costPointsRate = $cost * 0.01;
        // } elseif ($level >= 11 && $level <= 20) {
        //     $costPointsRate = $cost * 0.007;
        // } elseif ($level >= 21) {
        //     $costPointsRate = $cost * 0.0065;
        // }
        // 
        
        if ($level == 1) {
            $costPointsRate = 2; // 100
        } elseif ($level >= 2 && $level <= 5) {
            $costPointsRate = 1; // 50
        } elseif ($level >= 6 && $level <= 10) {
            $costPointsRate = 0.3;
        } elseif ($level >= 11 && $level <= 15) {
            $costPointsRate = 0.2;
        } elseif ($level >= 16) {
            $costPointsRate = 0.1;
        }

        return $costPointsRate;
    }

    private static function logAddingUnilevelPoints($referredUserId, $referrerUserId, $points, $level)
    {
        $membershipPointLog = new MembershipPointLog;

        $membershipPointLog->referred_user_id = $referredUserId;
        $membershipPointLog->referrer_user_id = $referrerUserId;
        $membershipPointLog->points = $points;
        $membershipPointLog->membership_type = 'unilevel-points';
        $membershipPointLog->description = 'unilevel-points from level-' . $level;

        $membershipPointLog->save();
    }

    public static function deny($id)
    {
        $membership = Membership::with(self::$modelRelationships)
                ->where('id', $id)
                ->first();

        $user = User::where('id', $membership->user_id)->first();

        $user->email = $user->email . '-denied';
        $user->save();

        $membership->status = 'denied';
        $membership->paid_status = 'denied';
        $membership->denied_at = date('Y-m-d H:i:s');
        $membership->save();

        return $membership;
    }

    public static function downlines($userId, $level = 1)
    {
        $membership = Membership::with(self::$modelRelationships)
            ->where('user_id', $userId)
            ->first();

        $mainDownlines = [];
        $dynamicReferralLinks = [];

        if ($level == 1) {
            $downlines = Membership::with(self::$modelRelationships)
                ->where('referral_id', $membership->referral_link)
                ->get();

            if (!$downlines->isEmpty()) {
                foreach ($downlines as $downline) {
                    $mainDownlines[] = $downline;
                }
            }
        } else {
            for ($i = 1; $i <= 30; $i++) {
                $dynamicReferralLinksDummy = [];

                if ($i == 1) {
                    $downlines = Membership::with(self::$modelRelationships)
                        ->where('referral_id', $membership->referral_link)
                        ->get();

                    if (!$downlines->isEmpty()) {
                        foreach($downlines as $downline) {
                            $dynamicReferralLinksDummy[] = $downline->referral_link;
                        }
                    }

                    $dynamicReferralLinks = $dynamicReferralLinksDummy;
                } else {
                    if (count($dynamicReferralLinks) <= 0) {
                        break;
                    }

                    $downlines = Membership::with(self::$modelRelationships)
                        ->whereIn('referral_id', $dynamicReferralLinks)
                        ->get();

                    if (!$downlines->isEmpty()) {
                        if ($i >= $level) {
                            foreach($downlines as $downline) {
                                $mainDownlines[] = $downline;
                            }

                            break;
                        } else {
                            foreach($downlines as $downline) {
                                $dynamicReferralLinksDummy[] = $downline->referral_link;
                            }

                            $dynamicReferralLinks = $dynamicReferralLinksDummy;
                        }
                    }
                }
            }
        }

        return $mainDownlines;
    }

    public static function addInterest()
    {
        \Log::info('ran addInterest MembershipModule function');

        $users = User::where('status', 'activated')->get();

        $userIds = [];

        foreach ($users as $user) {
            $userIds[] = $user->id;
        }

        $memberships = Membership::with(self::$modelRelationships)
            ->whereIn('user_id', $userIds)
            ->get();

        foreach ($memberships as $membership) {
            $newMembership = Membership::where('id', $membership->id)->first();

            $newMembership->interest += self::calculateInterest($membership->plan->cost);
            $newMembership->save();
        }
    }

    private static function calculateInterest($membershipCost)
    {
        return $membershipCost * 0.01;
    }
}
