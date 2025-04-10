<?php

namespace App\Providers;

use App\Models\User;
use App\Policies\UserPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        User::class => UserPolicy::class,
    ];

    /**
     * Bootstrap any application authentication / authorization services.
     */
    public function boot(): void
    {
        Gate::define('system-admin', function ($user) {
            return in_array(
                session('user_role'),
                ['super_admin', 'admin']
            );
        });
    }
}
