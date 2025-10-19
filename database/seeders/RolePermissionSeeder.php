<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = Role::create(['name' => 'Admin']);
        $hrd = Role::create(['name' => 'HRD']);
        $accountant = Role::create(['name' => 'Accountant']);

        $permissions = [
            'create companies',
            'edit companies',
            'show companies',
            'delete companies',
            'create departments',
            'edit departments',
            'show departments',
            'delete departments',
            'create designations',
            'edit designations',
            'show designations',
            'delete designations',
            'create employees',
            'edit employees',
            'show employees',
            'delete employees',
            'create contracts',
            'edit contracts',
            'show contracts',
            'delete contracts',
            'show payrolls',
            'generate payrolls',
            'show salaries',
            'show payments',
            'create payments',
            'delete payments',
            'create users',
            'show users',
            'edit users',
            'delete users'
        ];

        foreach ($permissions as $perm) {
            Permission::create(['name' => $perm]);
        }

        $admin->givePermissionTo($permissions);

        $permissions = [
            'create departments',
            'edit departments',
            'show departments',
            'delete departments',
            'create designations',
            'edit designations',
            'show designations',
            'delete designations',
            'create employees',
            'edit employees',
            'show employees',
            'delete employees',
            'create contracts',
            'edit contracts',
            'show contracts',
            'delete contracts',
            'show payrolls',
            'generate payrolls',
            'show salaries',
        ];

        $hrd->givePermissionTo($permissions);

        $permissions = [
            'show payments',
            'create payments',
            'delete payments',
        ];

        $accountant->givePermissionTo($permissions);

        $user = User::find(1);
        $user->assignRole('Admin');
    }
}
