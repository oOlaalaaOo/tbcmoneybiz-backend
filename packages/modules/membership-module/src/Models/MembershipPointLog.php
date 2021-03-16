<?php

namespace Modules\MembershipModule\Models;

use Illuminate\Database\Eloquent\Model;

class MembershipPointLog extends Model
{
    protected $table = 'membership_point_logs';

    public function plan()
    {
    	return $this->belongsTo('Modules\PlanModule\Models\Plan', 'plan_id', 'id');
    }
}
