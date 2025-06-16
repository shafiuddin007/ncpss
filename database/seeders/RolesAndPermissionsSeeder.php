<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = Role::create(['name' => 'admin']);
        $user = Role::create(['name' => 'user']);

        Permission::create(['name' => 'dashboard'])->assignRole([$admin, $user]);
        Permission::create(['name' => 'member'])->assignRole($admin);
        Permission::create(['name' => 'product'])->assignRole($admin);
        Permission::create(['name' => 'application'])->assignRole($admin);

        $user = User::find(4);
        $user->assignRole('admin');
    }
}
