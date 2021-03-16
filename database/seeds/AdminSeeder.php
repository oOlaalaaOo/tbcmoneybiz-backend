<?php

use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'name' => 'admin',
            'email' => 'admin',
            'password' => bcrypt('nonsat5454'),
            'sub_password' => 'nonsat5454',
            'status' => 'activated',
            'is_admin' => 1
        ]);
    }
}
