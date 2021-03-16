<?php

use Illuminate\Database\Seeder;

class LeaderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'name' => 'redlion',
            'email' => 'redlion',
            'password' => bcrypt('redlion101'),
            'sub_password' => 'redlion101',
            'status' => 'deactivated',
            'is_admin' => 0
        ]);

        DB::table('memberships')->insert([
        	'user_id' => 2,
        	'plan_id' => 1,
        	'referral_link' => uniqid(),
        	'referral_id' => 'leader',
        	'unilevel_points' => 0,
        	'referral_points' => 0,
        	'interest' => 0,
        	'transaction_hash' => 'leader',
        	'admin_btc_wallet' => 'leader',
        	'current_btc_value' => 0,
        	'current_usd_value' => 0,
        	'status' => 'pending'
        ]);
    }
}
