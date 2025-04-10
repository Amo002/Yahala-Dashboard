<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Services\Admin\UserService;
use App\Models\Merchant;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request, UserService $userService)
    {
        $users = $userService->getUsersFiltered($request->input('merchant_id'));
        $merchants = Merchant::select('id', 'name')->get();

        return view('admin.users.index', compact('users', 'merchants'));
    }
}
