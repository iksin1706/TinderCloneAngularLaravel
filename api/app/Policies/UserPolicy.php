<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\Response;

class UserPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user)
    {
        return $user->isAdmin();
    }

    public function view(User $user, User $model)
    {
        return $user->isAdmin() || $user->id === $model->id;
    }

    public function update(User $user, User $model)
    {
        return $user->isAdmin() || $user->id === $model->id;
    }
}
