<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Merchant;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\PermissionRegistrar;

class CoreSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Create System Merchant first
        $systemMerchant = Merchant::firstOrCreate(['id' => 1], [
            'name' => 'System',
            'address' => 'HQ',
            'admin_id' => null,
            'created_by' => null,
            'active' => true,
        ]);

        // 2. Create Super Admin User
        $superAdmin = User::firstOrCreate(
            ['email' => 'admin@ex.com'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('admin'),
                'merchant_id' => $systemMerchant->id,
            ]
        );

        // 3. Set admin_id for merchant
        $systemMerchant->update(['admin_id' => $superAdmin->id]);
        

        // 4. Tell Spatie to use merchant_id as team_id context
        app(PermissionRegistrar::class)->setPermissionsTeamId($systemMerchant->id);

        // 5. Create Role
        $superAdminRole = Role::firstOrCreate([
            'name' => 'super_admin',
            'guard_name' => 'web',
            'merchant_id' => $systemMerchant->id,
            'created_by' => $superAdmin->id,
        ], [
            'label' => 'Super Admin',
            'type' => 1, // 1 = system role
            'privilege_level' => 1, // 1 = highest privilege (super admin)
        ]);


        // 6. Define permissions
        $permissions = [
            ['name' => 'manage-users', 'label' => 'Manage Users', 'group' => 'users'],
            ['name' => 'manage-merchants', 'label' => 'Manage Merchants', 'group' => 'merchants'],
            ['name' => 'manage-roles', 'label' => 'Manage Roles', 'group' => 'roles'],
            ['name' => 'manage-permissions', 'label' => 'Manage Permissions', 'group' => 'roles'],
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

        // 7. Assign Role to Super Admin under current merchant context
        $superAdmin->assignRole($superAdminRole);
    }
}
