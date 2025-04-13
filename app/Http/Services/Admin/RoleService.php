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
            ->where('roles.id', '!=', 1) // exclude super_admin
            ->orderBy('roles.name')
            ->get();
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
        $role = Role::with('permissions')->findOrFail($roleId);

        // Set Spatie team context
        app(PermissionRegistrar::class)->setPermissionsTeamId(1); // system team

        $authUser = auth()->user();
        $authRoles = $authUser->roles()
            ->wherePivot('merchant_id', 1)
            ->with('permissions:id,name,label,group')
            ->get();

        $assignablePermissions = $authRoles
            ->pluck('permissions')
            ->flatten()
            ->unique('id');

        // Group by `group` (fallback to 'Other' if null)
        $grouped = $assignablePermissions
            ->groupBy(fn($p) => $p->group ?? 'Other')
            ->map(fn($items) => $items->map(fn($p) => [
                'id' => $p->id,
                'name' => $p->name,
                'label' => $p->label,
            ])->values())
            ->toArray();

        $users = User::select('id', 'name', 'email')
            ->whereHas('roles', fn($q) => $q->where('id', $roleId))
            ->limit(5)
            ->get();

        return [
            'role' => $role,
            'permissions' => $role->permissions,
            'availablePermissions' => $grouped,
            'users' => $users,
        ];
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
}
