<?php

namespace Modules\PlanModule\Models;

use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    protected $table = 'plans';

    public function memberships()
    {
    	return $this->hasMany('Modules\MembershipModule\Models\Membership');
    }
}
