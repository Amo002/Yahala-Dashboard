<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Services\Auth\AuthService;

class LoginController extends Controller
{
    /**
     * Show the login form.
     */
    public function show()
    {
        if (auth()->check()) {
            return redirect()->route('admin.dashboard');
        }
        
        return view('auth.login');
    }

    /**
     * Handle login submission.
     */
    public function store(LoginRequest $request, AuthService $authService)
    {
        $result = $authService->login(
            $request->only('email', 'password'),
            $request->boolean('remember')
        );

        if (! $result['status']) {
            return back()
                ->withErrors([$result['error_type'] => $result['message']])
                ->withInput();
        }

        // Retrieve the roles from the result (an array of role names)
        $roles = $result['roles'] ?? [];

        if (in_array('super_admin', $roles) || in_array('admin', $roles)) {
            return redirect()->route('admin.dashboard')->with('status', $result['message']);
        } elseif (in_array('merchant', $roles) || in_array('merchant_admin', $roles)) {
            return redirect()->route('merchant.dashboard')->with('status', $result['message']);
        } else {
            return redirect()->route('welcome')->with('status', $result['message']);
        }
    }
}
