<?php

use Illuminate\Database\Seeder;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('roles')->insert([
            [
                'id' => 1,
                'name' => 'administrator',
                'guard_name' => 'web',
            ],
            [
                'id' => 2,
                'name' => 'authenticated_user',
                'guard_name' => 'web',
            ]
        ]);
    }
}
