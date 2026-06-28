<?php

namespace App\Jobs;

use App\Models\Ticket;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class SendTicketCompletedNotification implements ShouldQueue
{
    use Queueable;

    public function __construct(public Ticket $ticket) {}

    public function handle(): void
    {
        throw new \RuntimeException("Failed to send completion email for ticket #{$this->ticket->id}.");
    }
}
