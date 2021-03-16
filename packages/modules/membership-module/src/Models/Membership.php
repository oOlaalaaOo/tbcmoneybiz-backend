<?php

namespace Modules\MembershipModule\Models;

use Illuminate\Database\Eloquent\Model;

class Membership extends Model
{
    protected $table = 'memberships';

    public function user()
    {
    	return $this->belongsTo('App\User', 'user_id', 'id');
    }

    public function plan()
    {
    	return $this->belongsTo('Modules\PlanModule\Models\Plan', 'plan_id', 'id');
    }
}
