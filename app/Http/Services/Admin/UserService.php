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


    private function canManageRoles(): bool
    {
        app(PermissionRegistrar::class)->setPermissionsTeamId(1);

        $authUser = auth()->user();

        // Get the lowest privilege level among all roles
        $privilegeLevel = $authUser->roles
            ->pluck('privilege_level')
            ->filter() // in case null
            ->min() ?? 99;

        return $privilegeLevel <= 2;
    }


    public function updateUserProfile(User $user, array $data): array
    {
        try {
            $user->update($data);

            return [
                'status' => true,
                'message' => 'User profile updated successfully.'
            ];
        } catch (\Exception $e) {
            return [
                'status' => false,
                'message' => 'Failed to update user profile.'
            ];
        }
    }

    public function deleteUser(User $user): array
    {
        try {
            // Remove all roles first
            $user->roles()->detach();

            // Delete the user
            $user->delete();

            return [
                'status' => true,
                'message' => 'User deleted successfully.'
            ];
        } catch (\Exception $e) {
            return [
                'status' => false,
                'message' => 'Failed to delete user.'
            ];
        }
    }

    public function assignRolesToUser(User $user, array $data): array
    {
        if (! $this->canManageRoles()) {
            return [
                'status' => false,
                'message' => 'You do not have permission to assign roles.',
            ];
        }

        app(PermissionRegistrar::class)->setPermissionsTeamId(1);

        $roleIds = $data['role_ids'] ?? [];

        $validRoles = Role::whereIn('id', $roleIds)
            ->where('merchant_id', 1)
            ->pluck('name')
            ->toArray();

        $user->syncRoles($validRoles);

        return [
            'status' => true,
            'message' => count($validRoles)
                ? 'Roles assigned successfully.'
                : 'All roles removed from user.',
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

        // Detach all roles
        $user->roles()->detach();

        return [
            'status' => true,
            'message' => 'All roles unassigned successfully.',
        ];
    }
    public function destroyUser(User $user): array
    {

        app(PermissionRegistrar::class)->setPermissionsTeamId(1);


        // Optional: Prevent deletion of Super Admin (or other protected users)
        if ($user->id === 1) {
            return [
                'status'  => false,
                'message' => 'Super Admin cannot be deleted.',
            ];
        }

        try {
            // Detach all roles assigned to the user
            $user->roles()->detach();

            // Now delete the user
            $user->delete();

            return [
                'status'  => true,
                'message' => 'User deleted successfully.',
            ];
        } catch (\Exception $e) {
            // Optionally log the error for debugging
            // \Log::error($e->getMessage());
            return [
                'status'  => false,
                'message' => 'Failed to delete user.',
            ];
        }
    }
}
