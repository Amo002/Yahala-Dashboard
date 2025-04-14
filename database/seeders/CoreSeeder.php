<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Merchant;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class CoreSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Create System Merchant
        $systemMerchant = Merchant::firstOrCreate(['id' => 1], [
            'name' => 'System',
            'address' => 'HQ',
            'admin_id' => null,
            'created_by' => null,
            'active' => true,
        ]);

        // 2. Create Super Admin User
        $superAdmin = User::firstOrCreate(
            ['email' => 'superadmin@ex.com'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('admin'),
                'merchant_id' => $systemMerchant->id,
            ]
        );

        // 3. Create 2 more users (no roles yet)
        User::firstOrCreate(
            ['email' => 'admin@ex.com'],
            [
                'name' => 'Admin',
                'password' => Hash::make('admin'),
                'merchant_id' => $systemMerchant->id,
            ]
        );

        User::firstOrCreate(
            ['email' => 'editor@ex.com'],
            [
                'name' => 'Editor',
                'password' => Hash::make('editor'),
                'merchant_id' => $systemMerchant->id,
            ]
        );

        User::firstOrCreate(
            ['email' => 'viewer@ex.com'],
            [
                'name' => 'Viewer',
                'password' => Hash::make('viewer'),
                'merchant_id' => $systemMerchant->id,
            ]
        );

        // 4. Set admin_id on merchant
        $systemMerchant->update(['admin_id' => $superAdmin->id]);

        // 5. Tell Spatie to use team context
        app(PermissionRegistrar::class)->setPermissionsTeamId($systemMerchant->id);

        // 6. Create Super Admin Role
        $superAdminRole = Role::firstOrCreate([
            'name' => 'super_admin',
            'guard_name' => 'web',
            'merchant_id' => $systemMerchant->id,
            'created_by' => $superAdmin->id,
        ], [
            'label' => 'Super Admin',
            'type' => 1,
            'privilege_level' => 1,
        ]);

        // 7. Create and assign permissions
        $permissions = [
            // User permissions
            ['name' => 'view-users', 'label' => 'View Users', 'group' => 'users'],
            ['name' => 'create-users', 'label' => 'Create Users', 'group' => 'users'],
            ['name' => 'edit-users', 'label' => 'Edit Users', 'group' => 'users'],
            ['name' => 'delete-users', 'label' => 'Delete Users', 'group' => 'users'],

            // Merchant permissions
            ['name' => 'view-merchants', 'label' => 'View Merchants', 'group' => 'merchants'],
            ['name' => 'create-merchants', 'label' => 'Create Merchants', 'group' => 'merchants'],
            ['name' => 'edit-merchants', 'label' => 'Edit Merchants', 'group' => 'merchants'],
            ['name' => 'delete-merchants', 'label' => 'Delete Merchants', 'group' => 'merchants'],

            // Role & permission management
            ['name' => 'view-roles', 'label' => 'View Roles', 'group' => 'roles'],
            ['name' => 'create-roles', 'label' => 'Create Roles', 'group' => 'roles'],
            ['name' => 'edit-roles', 'label' => 'Edit Roles', 'group' => 'roles'],
            ['name' => 'delete-roles', 'label' => 'Delete Roles', 'group' => 'roles'],
            ['name' => 'assign-permissions', 'label' => 'Assign Permissions', 'group' => 'roles'],
        ];

        foreach ($permissions as $perm) {
            $permission = Permission::firstOrCreate([
                'name' => $perm['name'],
                'guard_name' => 'web',
            ], [
                'label' => $perm['label'],
                'group' => $perm['group'],
                'created_by' => $superAdmin->id,
            ]);

            $superAdminRole->givePermissionTo($permission);
        }

        // 8. Assign Super Admin Role to User
        $superAdmin->assignRole($superAdminRole);
    }
}
