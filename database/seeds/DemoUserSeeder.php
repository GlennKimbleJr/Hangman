<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DemoUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'name' => 'Demo',
            'email' => 'demo@example.org',
            'password' => bcrypt('demo'),
        ]);
    }
}
