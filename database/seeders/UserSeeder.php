<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
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
            'thumb' => '',
        ]);

        $role = Role::create([
            'name' => 'Super Admin',
            'guard_name' => 'web',
            'user_add' => 1,
        ]);
        // $permissions = Permission::pluck('id','id')->all();
        // $role->syncPermissions($permissions);
        $user->assignRole([$role->id]);
    }
}