<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AssignUserRoleRequest;
use App\Http\Requests\Admin\UpdateUserProfileRequest;
use App\Http\Services\Admin\UserService;
use App\Models\User;

class UserController extends Controller
{
    public function index(UserService $userService)
    {
        $users = $userService->getSystemUsers();

        return view('admin.users.index', compact('users'));
    }

    public function manage(User $user, UserService $userService)
    {

        $data = $userService->getSystemUserManageData($user);

        return view('admin.users.manage', $data);
    }

    public function assignRole(AssignUserRoleRequest $request, User $user, UserService $userService)
    {
        $result = $userService->assignRolesToUser($user, $request->validated());

        return back()->with($result['status'] ? 'status' : 'error', $result['message']);
    }


    public function unassignRole(User $user, UserService $userService)
    {
        $result = $userService->unassignUserRole($user);

        return back()->with($result['status'] ? 'status' : 'error', $result['message']);
    }


    public function update(UpdateUserProfileRequest $request, User $user, UserService $userService)
    {
        $result = $userService->updateUserProfile($user, $request->validated());

        return back()->with($result['status'] ? 'status' : 'error', $result['message']);
    }
}
