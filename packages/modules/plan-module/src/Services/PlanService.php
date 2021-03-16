<?php

namespace Modules\PlanModule\Services;

use Modules\PlanModule\Models\Plan;

class PlanService
{
    private static $modelRelationships = [
        'memberships'
    ];

    public static function getAll($params = [], $offset = 0, $limit = 10, $order_by = 'id', $order_type = 'desc')
    {
        $plans = Plan::with(self::$modelRelationships)
                    ->when(isset($params['name']), function ($query) use ($params) {
                        return $query->where('name', 'LIKE', '%' . $params['name'] . '%');
                    })
                    ->offset($offset)
                    ->limit($limit)
                    ->orderBy($order_by, $order_type)
                    ->get();

        \Log::info('plan:get-all ' . \json_encode($plans));

        return [
            'data'      => $plans,
            'offset'    => $offset,
            'limit'     => $limit 
        ];
    }

    public static function getOne($params = [], $order_by = 'id', $order_type = 'asc')
    {
        $plan = Plan::with(self::$modelRelationships)
                    ->when(isset($params['id']), function ($query) use ($params) {
                        return $query->where('id', $params['id']);
                    })
                    ->when(isset($params['name']), function ($query) use ($params) {
                        return $query->where('name', $params['name']);
                    })
                    ->orderBy($order_by, $order_type)
                    ->first();

        \Log::info('plan:get-one ' . json_encode($plan));

        return $plan;
    }

    public static function create($params = [])
    {
        if (!isset($params['name'])) {
            throw new \Exception('name is not specified', 1);
        }

        if (!isset($params['cost'])) {
            throw new \Exception('cost is not specified', 1);
        }

        if (!isset($params['points'])) {
            throw new \Exception('points is not specified', 1);
        }

        $plan = new Plan;

        $plan->name = $params['name'];
        $plan->cost = $params['cost'];
        $plan->points = $params['points'];

        if (!$plan->save()) {
            throw new \Exception('error in saving plan', 1);
        }

        \Log::info('plan:created ' . \json_encode($plan));

        return $plan;
    }

    public static function update($params = [], $id)
    {
        $plan = Plan::find($id);

        if (isset($params['name'])) {
            $plan->name = $params['name'];
        }

        if (isset($params['plan_cost'])) {
            $plan->plan_cost = $params['plan_cost'];
        }

        if (isset($params['plan_points'])) {
            $plan->plan_points = $params['plan_points'];
        }

        if (!$plan->save()) {
            throw new \Exception('error in saving plan', 1);
        }

        \Log::info('plan:updated ' . \json_encode($plan));

        return $plan;
    }

    public static function delete($id = null)
    {
        if (!$id) {
            throw new \Exception('id is not specified', 1);
        }

        $plan = Plan::where('id', $id)->delete();

        if (!$plan) {
            throw new \Exception('error in deleting plan-detail', 1);
        }

        \Log::info('plan:deleted ' . json_encode($plan));

        return $plan;
    }
}
