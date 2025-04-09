<?php

namespace Database\Seeders;

use App\Models\Team;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\PermissionRegistrar;

class CoreSeeder extends Seeder
{
    public function run(): void
    {
        // Create teams
        $systemTeam = Team::firstOrCreate(['name' => 'system_team']);
        $merchantTeam = Team::firstOrCreate(['name' => 'merchant_team']);

        // Set global Spatie team context
        app(PermissionRegistrar::class)->setPermissionsTeamId($systemTeam->id);

        // Create Super Admin User
        $superAdmin = User::firstOrCreate(
            ['email' => 'admin@yahala.com'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('password'),
                'team_id' => $systemTeam->id,
            ]
        );

        // Create Super Admin Role
        $superAdminRole = Role::firstOrCreate([
            'name' => 'Super Admin',
            'slug' => 'super_admin',
            'guard_name' => 'web',
            'team_id' => $systemTeam->id,
            'merchant_id' => null, // global
            'created_by' => $superAdmin->id,
        ]);

        // Permission group: system
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
                'team_id' => $systemTeam->id,
            ], [
                'label' => $perm['label'],
                'group' => $perm['group'],
                'merchant_id' => null,
                'created_by' => $superAdmin->id,
            ]);

            $superAdminRole->givePermissionTo($permission);
        }

        // Assign role to Super Admin
        $superAdmin->assignRole($superAdminRole);
    }
}
