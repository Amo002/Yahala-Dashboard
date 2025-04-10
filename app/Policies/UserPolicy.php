<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Support\Facades\Log;

class UserPolicy
{


    public function viewAny(User $user): bool
    {

        if ($user->hasRole('super_admin') && $user->team_id === 1) {
            return true;
        }

        return $user->hasRole('merchant_admin');
    }

    // Same logic for other actions
    public function view(User $user, User $model): bool
    {
        return $user->id === $model->id;
    }

    public function create(User $user): bool
    {
        return $user->hasRole('merchant_admin');
    }

    public function update(User $user, User $model): bool
    {
        return $user->id === $model->id;
    }

    public function delete(User $user, User $model): bool
    {
        return $user->hasRole('merchant_admin') && $user->id !== $model->id;
    }

    public function forceDelete(User $user, User $model): bool
    {
        return false;
    }
}
