<?php

namespace Modules\CashoutModule\Models;

use Illuminate\Database\Eloquent\Model;

class Cashout extends Model
{
    protected $table = 'cashouts';

    public function user()
    {
    	return $this->belongsTo('App\User', 'user_id', 'id');
    }

    public function membership()
    {
    	return $this->belongsTo('Modules\MembershipModule\Models\Membership', 'membership_id', 'id');
    }
}
