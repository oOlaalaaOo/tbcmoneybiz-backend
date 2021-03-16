<?php

namespace Modules\SettingModule\Services;

use Modules\SettingModule\Models\Setting;

class SettingService
{
    public static function getAll($params = [], $offset = 0, $limit = 10, $order_by = 'id', $order_type = 'desc')
    {
        $settings = Setting::when(isset($params['name']), function ($query) use ($params) {
                        return $query->where('name', 'LIKE', '%' . $params['name'] . '%');
                    })
                    ->offset($offset)
                    ->limit($limit)
                    ->orderBy($order_by, $order_type)
                    ->get();

        \Log::info('setting:get-all ' . \json_encode($settings));

        return [
            'data'      => $settings,
            'offset'    => $offset,
            'limit'     => $limit 
        ];
    }

    public static function getOne($params = [], $order_by = 'id', $order_type = 'asc')
    {
        $setting = Setting::with(self::$modelRelationships)
                    ->when(isset($params['id']), function ($query) use ($params) {
                        return $query->where('id', $params['id']);
                    })
                    ->when(isset($params['name']), function ($query) use ($params) {
                        return $query->where('name', $params['name']);
                    })
                    ->orderBy($order_by, $order_type)
                    ->first();

        \Log::info('setting:get-one ' . json_encode($setting));

        return $setting;
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

        $setting = new Setting;

        $setting->name = $params['name'];
        $setting->cost = $params['cost'];
        $setting->points = $params['points'];

        if (!$setting->save()) {
            throw new \Exception('error in saving setting', 1);
        }

        \Log::info('setting:created ' . \json_encode($setting));

        return $setting;
    }

    public static function update($params = [], $id)
    {
        $setting = Setting::find($id);

        if (isset($params['name'])) {
            $setting->name = $params['name'];
        }

        if (isset($params['cost'])) {
            $setting->cost = $params['cost'];
        }

        if (isset($params['points'])) {
            $setting->points = $params['points'];
        }

        if (!$setting->save()) {
            throw new \Exception('error in saving setting', 1);
        }

        \Log::info('setting:updated ' . \json_encode($setting));

        return $setting;
    }

    public static function delete($id = null)
    {
        if (!$id) {
            throw new \Exception('id is not specified', 1);
        }

        $setting = Setting::where('id', $id)->delete();

        if (!$setting) {
            throw new \Exception('error in deleting setting-detail', 1);
        }

        \Log::info('setting:deleted ' . json_encode($setting));

        return $setting;
    }
}
