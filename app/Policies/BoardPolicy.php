<?php

namespace App\Policies;

use App\Models\Board;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\Access\Response;


class BoardPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Board $board): bool
    {
        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return Auth::check();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Board $board): Response
    {
        return $board->isAdmin($user) ? Response::allow() : Response::deny('Only board admins can update the board.');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Board $board): Response
    {
        return $board->owner()->is($user) ? Response::allow() : Response::deny('Only board owner can delete the board.');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Board $board): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Board $board): bool
    {
        return false;
    }
}
