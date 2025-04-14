<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AssignPermissionRequest;
use App\Http\Requests\Admin\CreateRoleRequest;
use App\Http\Requests\Admin\RoleAssignUsersRequest;
use App\Http\Requests\Admin\UpdateRoleRequest;
use App\Http\Services\Admin\RoleService;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    protected RoleService $roleService;

    public function __construct(RoleService $roleService)
    {
        $this->roleService = $roleService;
    }

    public function index()
    {
        $roles = $this->roleService->getSystemRoles();
        return view('admin.roles.index', compact('roles'));
    }

    public function store(CreateRoleRequest $request)
    {
        $result = $this->roleService->createSystemRole($request->validated());

        return redirect()
            ->route('admin.roles.index')
            ->with($result['status'] ? 'status' : 'error', $result['message']);
    }

    public function manage($roleId)
    {
        $data = $this->roleService->getRoleManageData($roleId);
        return view('admin.roles.manage', $data);
    }

    public function assignPermissions(AssignPermissionRequest $request, $roleId)
    {
        $result = $this->roleService->assignPermissionsToRole($roleId, $request->validated());

        return redirect()
            ->route('admin.roles.manage', $roleId)
            ->with($result['status'] ? 'status' : 'error', $result['message']);
    }


    public function update(UpdateRoleRequest $request, $roleId)
    {
        $result = $this->roleService->updateRole($roleId, $request->validated());

        return back()->with($result['status'] ? 'status' : 'error', $result['message']);
    }

    public function destroy($roleId)
    {
        $result = $this->roleService->deleteRole($roleId);

        return redirect()->route('admin.roles.index')
            ->with($result['status'] ? 'status' : 'error', $result['message']);
    }

    public function unassignUser($roleId, $userId)
    {
        $result = $this->roleService->unassignUserFromRole($roleId, (int)$userId);

        return redirect()->back()
            ->with($result['status'] ? 'status' : 'error', $result['message']);
    }

    public function assignUsers(RoleAssignUsersRequest $request, Role $role)
    {
        $result = $this->roleService->assignUsers($role, $request->validated());
        return $result['status']
            ? redirect()->back()->with('status', $result['message'])
            : redirect()->back()->with('error', $result['message']);
    }
}
