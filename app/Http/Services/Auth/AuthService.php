<?php

namespace App\Http\Services\Auth;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\PermissionRegistrar;

class AuthService
{
    public function login(array $credentials, bool $remember = false): array
    {
        $user = User::where('email', $credentials['email'])->first();

        if (! $user) {
            return [
                'status' => false,
                'message' => 'No account found for this email.',
                'error_type' => 'email',
            ];
        }

        // Set the Spatie team context BEFORE fetching roles
        app(PermissionRegistrar::class)->setPermissionsTeamId($user->merchant_id);

        // Check password
        if (! Hash::check($credentials['password'], $user->password)) {
            return [
                'status' => false,
                'message' => 'Incorrect password.',
                'error_type' => 'password',
            ];
        }

        // Attempt login
        if (! Auth::attempt($credentials, $remember)) {
            return [
                'status' => false,
                'message' => 'Authentication failed.',
                'error_type' => 'email',
            ];
        }

        // Get role name (one role per user, as string)
        $role = $user->getRoleNames()->first();


        Session::put('user_role', $role);

        return [
            'status' => true,
            'message' => 'Login successful.',
            'user' => $user,
            'role' => $role,
        ];
    }
}
