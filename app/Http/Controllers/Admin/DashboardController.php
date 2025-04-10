<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;
use App\Models\User;
use Spatie\Permission\PermissionRegistrar;

class DashboardController extends Controller
{
    public function index()
    {
        app(PermissionRegistrar::class)->setPermissionsTeamId(auth()->user()->team_id);

        Gate::authorize('viewAny', User::class);

        return view('admin.dashboard');
    }
}
