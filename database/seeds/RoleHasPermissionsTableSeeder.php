<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
class RoleHasPermissionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permissions = Permission::get()->pluck('id');
        foreach ($permissions as $key => $permission_id){
            DB::table('role_has_permissions')->insert([
                'permission_id' => $permission_id,
                'role_id' => 1,
            ]);
        }

    }
}
