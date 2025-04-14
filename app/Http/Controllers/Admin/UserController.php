<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AssignUserRoleRequest;
use App\Http\Requests\Admin\UpdateUserProfileRequest;
use App\Http\Services\Admin\UserService;
use App\Models\User;
use Illuminate\Support\Facades\Gate;

class UserController extends Controller
{
    public function index(UserService $userService)
    {
        // Use Gate::authorize() to check 'viewAny' on the User class.
        Gate::authorize('viewAny', User::class);
        
        $users = $userService->getSystemUsers();
        return view('admin.users.index', compact('users'));
    }

    public function manage(User $user, UserService $userService)
    {
        // Check if the current user can view this specific user.
        Gate::authorize('view', $user);
        
        $data = $userService->getSystemUserManageData($user);
        return view('admin.users.manage', $data);
    }

    public function assignRole(AssignUserRoleRequest $request, User $user, UserService $userService)
    {
        // Check if the user is authorized to update the given user.
        Gate::authorize('update', $user);
        
        $result = $userService->assignRolesToUser($user, $request->validated());
        return back()->with($result['status'] ? 'status' : 'error', $result['message']);
    }

    public function unassignRole(User $user, UserService $userService)
    {
        // For unassigning roles, use the 'delete' policy.
        Gate::authorize('delete', $user);
        
        $result = $userService->unassignUserRole($user);
        return back()->with($result['status'] ? 'status' : 'error', $result['message']);
    }

    public function update(UpdateUserProfileRequest $request, User $user, UserService $userService)
    {
        // Check if the user can update their profile.
        Gate::authorize('update', $user);
        
        $result = $userService->updateUserProfile($user, $request->validated());
        return back()->with($result['status'] ? 'status' : 'error', $result['message']);
    }

    public function destroy(User $user, UserService $userService)
    {
        // Check if the user is authorized to delete this user.
        Gate::authorize('delete', $user);
        
        $result = $userService->destroyUser($user);
        return redirect()
            ->route('admin.users.index')
            ->with($result['status'] ? 'status' : 'error', $result['message']);
    }
}
