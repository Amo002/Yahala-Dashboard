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

        // Role-based redirection
        switch ($result['role']) {
            case 'super_admin':
            case 'admin':
                return redirect()->route('admin.dashboard')->with('status', $result['message']);
            
            case 'merchant':
                return redirect()->route('merchant.dashboard')->with('status', $result['message']);
            
            default:
                return redirect()->route('welcome')->with('status', $result['message']);
        }
    }
}
