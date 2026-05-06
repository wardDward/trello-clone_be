<?php

namespace App\Policies;

use App\Models\Board;
use App\Models\BoardList;
use App\Models\Card;
use App\Models\User;
use Illuminate\Auth\Access\Response;
use Illuminate\Support\Facades\Gate;

class CardPolicy
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
    public function view(User $user, Card $card): bool
    {
        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user, BoardList $boardList): Response
    {
        return Gate::allows('belongs-to-board', $boardList->board) ? Response::allow() : Response::deny('Only members of the board can create cards.');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Card $card): Response
    {
        return Gate::allows('belongs-to-board', $card->boardList->board) ? Response::allow() : Response::deny('Only members of the board can update cards.');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Card $card): Response
    {
        return Gate::allows('board-admin', $card->boardList->board) ? Response::allow() : Response::deny('Only board admins can delete cards.');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Card $card): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Card $card): bool
    {
        return false;
    }
}
