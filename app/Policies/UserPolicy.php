<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    /**
     * Retrieve the current user's roles.
     *
     * First, check if the roles are cached in the session (using the key "user_roles").
     * If not, fall back to retrieving the roles from the database.
     *
     * @param  \App\Models\User  $user
     * @return array
     */
    protected function getRoles(User $user): array
    {
        // When the user logs in, you should cache their roles:
        // e.g. session(['user_roles' => $user->getRoleNames()->toArray()]);
        return session()->has('user_roles')
            ? session('user_roles')
            : $user->getRoleNames()->toArray();
    }

    /**
     * Determine whether the user can view any users.
     *
     * Allow if the user has any of the roles: admin, super_admin, merchant_admin, or viewer.
     *
     * @param  \App\Models\User  $user
     * @return bool
     */
    public function viewAny(User $user): bool
    {
        $roles = $this->getRoles($user);
        return in_array('admin', $roles)
            || in_array('super_admin', $roles)
            || in_array('merchant_admin', $roles)
            || in_array('viewer', $roles);
    }

    /**
     * Determine whether the user can view the model.
     *
     * Only allow elevated roles (admin, super_admin, or merchant_admin) to view user details.
     * Viewers are not allowed to see any user details—even their own.
     *
     * @param  \App\Models\User  $user     The currently authenticated user.
     * @param  \App\Models\User  $model    The user being viewed.
     * @return bool
     */
    public function view(User $user, User $model): bool
    {
        $roles = $this->getRoles($user);
        if (in_array('admin', $roles) || in_array('super_admin', $roles) || in_array('merchant_admin', $roles)) {
            return true;
        }
        // Deny access for viewers even if they try to view their own record.
        return false;
    }

    /**
     * Determine whether the user can create users.
     *
     * Allow if the user is a merchant_admin, admin, or super_admin.
     *
     * @param  \App\Models\User  $user
     * @return bool
     */
    public function create(User $user): bool
    {
        $roles = $this->getRoles($user);
        return in_array('merchant_admin', $roles)
            || in_array('admin', $roles)
            || in_array('super_admin', $roles);
    }

    /**
     * Determine whether the user can update the model.
     *
     * Allow admins and super_admins to update any user;
     * others (e.g. merchant_admins or viewers) may update only their own profile.
     *
     * @param  \App\Models\User  $user     The currently authenticated user.
     * @param  \App\Models\User  $model    The user being updated.
     * @return bool
     */
    public function update(User $user, User $model): bool
    {
        $roles = $this->getRoles($user);
        // Elevated roles can update any user.
        if (in_array('admin', $roles) || in_array('super_admin', $roles)) {
            return true;
        }
        // Others can update only their own profile.
        return $user->id === $model->id;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * Allow deletion if the user has an elevated role (merchant_admin, admin, or super_admin),
     * and they cannot delete their own record.
     *
     * @param  \App\Models\User  $user     The currently authenticated user.
     * @param  \App\Models\User  $model    The user being deleted.
     * @return bool
     */
    public function delete(User $user, User $model): bool
    {
        $roles = $this->getRoles($user);
        return (in_array('merchant_admin', $roles)
                || in_array('admin', $roles)
                || in_array('super_admin', $roles))
            && $user->id !== $model->id;
    }

    /**
     * Determine whether the user can force delete the model.
     * Force delete is disabled.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\User  $model
     * @return bool
     */
    public function forceDelete(User $user, User $model): bool
    {
        return false;
    }
}
