<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class UserRolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        //membuat 3 user dengan role berbeda yaitu staf, spv, manager
        $default_user_value = [
            'email_verified_at' => now(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'remember_token' => Str::random(10),
        ];

        DB::beginTransaction();

        try {
            $staf = User::create(array_merge([
                'email' => 'staf@gmail.com',
                'name' => 'staf',
            ], $default_user_value));

            $spv = User::create(array_merge([
                'email' => 'spv@gmail.com',
                'name' => 'spv',
            ], $default_user_value));

            $manager = User::create(array_merge([
                'email' => 'manager@gmail.com',
                'name' => 'manager',
            ], $default_user_value));

            $role_staf = Role::create(['name' => 'staf']);
            $role_spv = Role::create(['name' => 'spv']);
            $role_manager = Role::create(['name' => 'manager']);

            $permision = Permission::create(['name' => 'read konfigurasi/roles']);
            $permision = Permission::create(['name' => 'create konfigurasi/roles']);
            $permision = Permission::create(['name' => 'update konfigurasi/roles']);
            $permision = Permission::create(['name' => 'delete konfigurasi/roles']);
            $permision = Permission::create(['name' => 'read konfigurasi/permissions']);
            $permision = Permission::create(['name' => 'read konfigurasi']);

            //set role for user
            $staf->assignRole('staf');
            $spv->assignRole('spv');
            $manager->assignRole('manager');

            //set permission to role
            $role_staf->givePermissionTo('read konfigurasi');
            $role_manager->givePermissionTo([
                'read konfigurasi/roles',
                'read konfigurasi/permissions',
                'create konfigurasi/roles',
                'update konfigurasi/roles',
                'delete konfigurasi/roles',
                'read konfigurasi'
            ]);

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollback();
        }
    }
}
