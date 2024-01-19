<?php

namespace App\Policies;

use App\Models\Event;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class EventPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->isAdmin() || $user->isEventManager();
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Event $event): bool
    {
        return $user->isAdmin() || $user->isEventManager();
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->isEventManager();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Event $event): bool
    {
        return $user->isEventManager();
    }

    /**
     * Determine whether the user can delete the model.
     */
        public function deleteAny(User $user): bool
    {
        return $user->isAdmin() || $user->isEventManager();
    }

    public function delete(User $user, Event $category): bool
    {
        return $user->isAdmin() || $user->isEventManager();
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restoreAny(User $user): bool
    {
        return $user->isAdmin() || $user->isEventManager();
    }

    public function restore(User $user, Event $category): bool
    {
        return $user->isAdmin() || $user->isEventManager();
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->isAdmin() || $user->isEventManager();
    }

    public function forceDelete(User $user, Event $category): bool
    {
        return $user->isAdmin() || $user->isEventManager();
    }
}
