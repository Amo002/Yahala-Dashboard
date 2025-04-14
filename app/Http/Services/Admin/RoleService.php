<?php

namespace App\Http\Services\Admin;

use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class RoleService
{
    public function getSystemRoles()
    {
        return Role::query()
            ->select('roles.*')
            ->with('permissions')
            ->where('roles.merchant_id', 1)
            ->where('roles.id', '!=', 1)
            ->orderBy('roles.name')
            ->get()
            ->map(function ($role) {
                $creator = User::find($role->created_by);
                $role->creator_name = $creator?->name ?? 'Unknown';
                return $role;
            });
    }


    public function createSystemRole(array $data): array
    {
        $authUser = auth()->user();
        app(PermissionRegistrar::class)->setPermissionsTeamId($authUser->merchant_id);

        if (! $authUser->hasRole('super_admin') || $authUser->merchant_id !== 1) {
            return [
                'status' => false,
                'message' => 'Only Super Admins can manage system roles.',
            ];
        }

        $rawName = $data['name'];
        $label = ucwords(str_replace(['_', '-'], ' ', $rawName));
        $name = strtolower(str_replace(' ', '_', $rawName));

        if (Role::where('name', $name)->where('merchant_id', 1)->exists()) {
            return [
                'status' => false,
                'message' => 'Role already exists in the system.',
            ];
        }

        Role::create([
            'name' => $name,
            'label' => $label,
            'type' => 1,
            'privilege_level' => 0,
            'merchant_id' => 1,
            'guard_name' => 'web',
            'created_by' => $authUser->id,
        ]);

        return [
            'status' => true,
            'message' => 'System role created successfully.',
        ];
    }


    public function getRoleManageData(int $roleId): array
    {
        $authUser = auth()->user();

        app(PermissionRegistrar::class)->setPermissionsTeamId($authUser->merchant_id);

        // Get the role
        $role = Role::with('permissions')->findOrFail($roleId);

        // Add creator name dynamically
        $creator = User::find($role->created_by);
        $role->creator_name = $creator?->name ?? 'Unknown';

        $userCount = $role->users()->count();

        // Get the most powerful (lowest) privilege level of the auth user
        $authPrivilege = $authUser->roles
            ->pluck('privilege_level')
            ->filter()
            ->min() ?? 99;

        // Restrict which users can be shown to assign this role to
        $allUsers = User::with('roles')
            ->where('id', '!=', 1)
            ->where('id', '!=', $authUser->id)
            ->where('merchant_id', $authUser->merchant_id)
            ->get()
            ->filter(function ($user) use ($authPrivilege, $roleId) {
                $userPrivilege = $user->roles->pluck('privilege_level')->filter()->min() ?? 999;
                $alreadyHasRole = $user->roles->contains('id', $roleId);
                return ($userPrivilege > $authPrivilege) || $alreadyHasRole;
            })
            ->values();

        return [
            'role' => $role,
            'permissions' => $role->permissions,
            'availablePermissions' => $this->getAssignablePermissions($authUser),
            'users' => $role->users()->select('id', 'name', 'email')->limit(5)->get(),
            'userCount' => $userCount,
            'allUsers' => $allUsers,
        ];
    }


    private function getAssignablePermissions($authUser)
    {
        app(PermissionRegistrar::class)->setPermissionsTeamId(1); // or merchant_id

        return $authUser->roles()
            ->with('permissions:id,name,label,group')
            ->get()
            ->pluck('permissions')
            ->flatten()
            ->unique('id')
            ->groupBy(fn($p) => $p->group ?? 'Other')
            ->map(fn($items) => $items->map(fn($p) => [
                'id' => $p->id,
                'name' => $p->name,
                'label' => $p->label,
            ])->values())
            ->toArray();
    }



    public function assignPermissionsToRole(int $roleId, array $data): array
    {
        $role = $this->findRole($roleId);

        $permissionIds = $data['permissions'] ?? [];

        if (!is_array($permissionIds)) {
            $permissionIds = [];
        }

        $this->syncPermissions($role, $permissionIds);

        return [
            'status' => true,
            'message' => 'Permissions updated successfully.',
        ];
    }


    public function findRole($roleId): Role
    {
        return Role::with('permissions')->findOrFail($roleId);
    }

    public function syncPermissions(Role $role, array $permissionIds): void
    {
        $permissions = Permission::whereIn('id', $permissionIds)->get();
        $role->syncPermissions($permissions);
    }

    public function updateRole(int $roleId, array $data): array
    {
        $role = $this->findRole($roleId);

        if (Role::where('name', $data['name'])
            ->where('merchant_id', 1)
            ->where('id', '!=', $roleId)
            ->exists()
        ) {
            return [
                'status' => false,
                'message' => 'A role with this name already exists in the system.',
            ];
        }

        $role->update([
            'name' => $data['name'],
            'label' => $data['label'],
        ]);

        return [
            'status' => true,
            'message' => 'Role info updated successfully.',
        ];
    }

    public function deleteRole(int $roleId): array
    {
        $role = $this->findRole($roleId);

        // Detach role from all users
        $role->users()->detach();

        $role->delete();

        return [
            'status' => true,
            'message' => 'Role deleted and unassigned from users.',
        ];
    }

    public function unassignUserFromRole(int $roleId, int $userId): array
    {
        // Find role by ID (using your existing method)
        $role = $this->findRole($roleId);

        // Detach this role from the user
        $role->users()->detach($userId);

        return [
            'status'  => true,
            'message' => 'User unassigned from the role successfully.',
        ];
    }

    public function assignUsers(Role $role, array $data): array
    {
        $authUser = auth()->user();

        // Set the merchant context for Spatie (very important)
        app(PermissionRegistrar::class)->setPermissionsTeamId($authUser->merchant_id);

        // Get users from request
        $userIds = $data['user_ids'] ?? [];
        $roleName = $role->name;

        // Get valid users in same merchant/team
        $users = User::whereIn('id', $userIds)
            ->where('merchant_id', $authUser->merchant_id)
            ->get();

        // Assign role to each selected user
        foreach ($users as $user) {
            $user->assignRole($roleName);
        }

        // Unassign the role from users that were not selected (but still in same team)
        User::where('merchant_id', $authUser->merchant_id)
            ->whereNotIn('id', $userIds)
            ->get()
            ->each(function ($user) use ($roleName) {
                if ($user->hasRole($roleName)) {
                    $user->removeRole($roleName);
                }
            });

        $message = empty($userIds)
            ? 'All users unassigned from this role.'
            : 'Users assigned to this role successfully.';

        return [
            'status' => true,
            'message' => $message,
        ];
    }
}
