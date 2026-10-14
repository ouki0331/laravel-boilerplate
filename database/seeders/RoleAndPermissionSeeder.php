<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RoleAndPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // Define permissions
        $permissions = [
            'manage-users',
            'manage-roles',
            'manage-schedules',
            'manage-appointments',
            'view-appointments',
        ];

        foreach ($permissions as $permissionName) {
            Permission::findOrCreate($permissionName, 'web');
        }

        // Admin role gets all permissions
        $adminRole = Role::findOrCreate('admin', 'web');
        $adminRole->syncPermissions(Permission::all());

        // Staff role gets schedule and appointment permissions
        $staffRole = Role::findOrCreate('staff', 'web');
        $staffRole->syncPermissions([
            'manage-schedules',
            'manage-appointments',
            'view-appointments',
        ]);

        // Customer role can only view their appointments
        $customerRole = Role::findOrCreate('customer', 'web');
        $customerRole->syncPermissions([
            'view-appointments',
        ]);
    }
}
