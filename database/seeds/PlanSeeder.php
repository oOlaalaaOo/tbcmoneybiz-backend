<?php

use Illuminate\Database\Seeder;

class PlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('plans')->insert([
            'name' => 'level-1',
            'cost' => 50,
            'points' => 100
        ]);

        DB::table('plans')->insert([
            'name' => 'level-2',
            'cost' => 100,
            'points' => 200
        ]);
    }
}
