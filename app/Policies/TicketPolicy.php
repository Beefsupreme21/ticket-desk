<?php

namespace App\Policies;

use App\Enums\TicketStatus;
use App\Models\Ticket;
use App\Models\User;

class TicketPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->canManageTickets();
    }

    public function view(User $user, Ticket $ticket): bool
    {
        if ($user->canManageTickets()) {
            return true;
        }

        return $ticket->assigned_to === $user->id;
    }

    public function create(User $user): bool
    {
        return $user->canManageTickets();
    }

    public function update(User $user, Ticket $ticket): bool
    {
        if ($user->canManageTickets()) {
            return true;
        }

        return $ticket->assigned_to === $user->id;
    }

    public function acceptNext(User $user): bool
    {
        return $user->isAdvisor();
    }

    public function complete(User $user, Ticket $ticket): bool
    {
        if ($ticket->status === TicketStatus::Resolved) {
            return false;
        }

        return $user->isAdvisor() && $ticket->assigned_to === $user->id;
    }
}
