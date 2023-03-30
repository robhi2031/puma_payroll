<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::create([
            'name' => 'Admin Sample',
            'username' => 'admin.sample',
            'password' => bcrypt('123456'),
            'email' => 'admin.sample@gmail.com',
            'phone_number' => '0',
            'thumb' => 'avatar-sample-01.jpg',
            'user_add' => 1,
        ]);

        $role = Role::create([
            'name' => 'Super Admin',
            'guard_name' => 'web',
            'user_add' => 1,
        ]);

        $permissionMenu = DB::table('permission_has_menus')->insert([
            'name' => 'Roles',
            'icon' => 'bi-shield-lock',
            'has_route' => 'Y',
            'route_name' => 'manage_roles',
            'has_child' => 'N',
            'is_crud' => 'Y',
            'order_line' => '1',
            'user_add' => 1,
        ]);

        $permission = Permission::create([
            'name' => 'roles-read',
            'fid_menu' => 1,
            'guard_name' => 'web',
            'user_add' => 1,
        ]);
        $permission = Permission::create([
            'name' => 'roles-create',
            'fid_menu' => 1,
            'guard_name' => 'web',
            'user_add' => 1,
        ]);
        $permission = Permission::create([
            'name' => 'roles-update',
            'fid_menu' => 1,
            'guard_name' => 'web',
            'user_add' => 1,
        ]);
        $permission = Permission::create([
            'name' => 'roles-delete',
            'fid_menu' => 1,
            'guard_name' => 'web',
            'user_add' => 1,
        ]);
        // $permissions = Permission::pluck('id','id')->all();
        // $role->syncPermissions($permissions);
        $role->givePermissionTo('roles-read');
        $role->givePermissionTo('roles-create');
        $role->givePermissionTo('roles-update');
        $role->givePermissionTo('roles-delete');

        $user->assignRole([$role->id]);
    }
}