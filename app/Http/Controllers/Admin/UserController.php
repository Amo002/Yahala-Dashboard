<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Services\Admin\UserService;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class UserController extends Controller
{
    public function index(Request $request, UserService $userService)
    {
        $users = $userService->getUsersFiltered(
            $request->input('team'),
            $request->input('merchant_id')
        );

        $merchants = \App\Models\Merchant::select('id', 'name')->get();

        return view('admin.users.index', compact('users', 'merchants'));
    }
}
