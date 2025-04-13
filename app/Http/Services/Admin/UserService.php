<?php

namespace App\Http\Services\Admin;

use App\Models\Merchant;
use App\Models\User;
use Spatie\Permission\PermissionRegistrar;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class UserService
{
    public function getSystemUsers()
    {
        app(PermissionRegistrar::class)->setPermissionsTeamId(1); // system merchant

        return User::with(['roles:id,name,label', 'merchant:id,name', 'inviter:id,name'])
            ->where('merchant_id', 1)
            ->where('id', '!=', 1) // Exclude Super Admin
            ->get();
    }

    public function getSystemUserManageData(User $user): array
    {
        app(PermissionRegistrar::class)->setPermissionsTeamId(1);

        $user->load([
            'roles' => fn($q) => $q->select('roles.id', 'roles.name', 'roles.label', 'roles.merchant_id'),
            'roles.permissions:id,name,label',
            'merchant:id,name', // for merchant display
            'inviter:id,name',
        ]);

        $availableRoles = Role::where('merchant_id', 1)
            ->where('id', '!=', 1) // exclude super_admin
            ->select('id', 'name', 'label')
            ->orderBy('label')
            ->get();

        return [
            'user' => $user,
            'availableRoles' => $availableRoles,
        ];
    }


    public function assignRoleToUser(User $user, int $roleId): array
    {
        if (! $this->canManageRoles()) {
            return [
                'status' => false,
                'message' => 'You do not have permission to assign roles.',
            ];
        }

        app(PermissionRegistrar::class)->setPermissionsTeamId(1);

        $role = Role::where('id', $roleId)
            ->where('merchant_id', 1)
            ->first();

        if (! $role) {
            return [
                'status' => false,
                'message' => 'Role not found or not assignable.',
            ];
        }

        if ($user->hasRole($role)) {
            return [
                'status' => false,
                'message' => 'User already has this role.',
            ];
        }

        $user->syncRoles([$role->name]);

        return [
            'status' => true,
            'message' => 'Role assigned successfully.',
        ];
    }


    public function unassignUserRole(User $user): array
    {
        if (! $this->canManageRoles()) {
            return [
                'status' => false,
                'message' => 'You do not have permission to unassign roles.',
            ];
        }

        app(PermissionRegistrar::class)->setPermissionsTeamId(1);

        $currentRole = $user->roles()->first();

        if (! $currentRole) {
            return [
                'status' => false,
                'message' => 'User has no role to unassign.',
            ];
        }

        $user->removeRole($currentRole->name);

        return [
            'status' => true,
            'message' => 'Role unassigned successfully.',
        ];
    }


    private function canManageRoles(): bool
    {
        app(PermissionRegistrar::class)->setPermissionsTeamId(1);

        $authUser = auth()->user();
        $authRole = $authUser->roles()->first(); // Assuming single role

        $privilegeLevel = $authRole?->privilege_level ?? 99;

        return $privilegeLevel <= 2;
    }

    public function updateUserProfile(User $user, array $data): array
    {
        try {
            $user->update([
                'name' => $data['name'],
                'email' => $data['email'],
                'merchant_id' => $data['merchant_id'],
            ]);

            return [
                'status' => true,
                'message' => 'User profile updated successfully.',
            ];
        } catch (\Throwable $e) {
            return [
                'status' => false,
                'message' => 'Failed to update user profile.',
            ];
        }
    }
}
