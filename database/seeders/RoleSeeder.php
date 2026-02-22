<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create Permissions
        $permissions = [
            'manage branding',
            'manage organizational units',
            'manage users',
            'access admin hub',
            'perform evaluations',
            'manage tickets',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Create Roles and Assign Permissions
        
        // Super Admin gets everything
        $superAdmin = Role::firstOrCreate(['name' => 'Super Admin']);
        // Note: We use a Gate::before in AuthServiceProvider for Super Admin to have all permissions

        $admin = Role::firstOrCreate(['name' => 'Admin']);
        $admin->givePermissionTo([
            'manage branding',
            'manage organizational units',
            'manage users',
            'access admin hub',
        ]);

        $evaluator = Role::firstOrCreate(['name' => 'Evaluator']);
        $evaluator->givePermissionTo([
            'perform evaluations',
        ]);

        $employee = Role::firstOrCreate(['name' => 'Employee']);
    }
}
