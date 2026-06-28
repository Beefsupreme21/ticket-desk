<?php

namespace App\Http\Controllers;

use App\Jobs\SendTicketCompletedNotification;
use App\Models\Ticket;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Http;
use RuntimeException;

class DemoErrorController extends Controller
{
    public function exception(): void
    {
        $this->ensureEnabled();

        throw new RuntimeException('Failed to build the manager ticket summary report.');
    }

    public function failedJob(): RedirectResponse
    {
        $this->ensureEnabled();

        $ticket = Ticket::query()->firstOrFail();

        SendTicketCompletedNotification::dispatch($ticket);

        return back()->with(
            'status',
            __('Notification job queued. Run a queue worker to process the failure.'),
        );
    }

    public function webhook(): RedirectResponse
    {
        $this->ensureEnabled();

        Http::timeout(5)
            ->post(config('demo.webhook_url'), [
                'event' => 'ticket.completed',
            ])
            ->throw();

        return back()->with('status', __('Webhook delivered successfully.'));
    }

    public function missingAssignee(): void
    {
        $this->ensureEnabled();

        $ticket = Ticket::query()
            ->whereNull('assigned_to')
            ->firstOrFail();

        $ticket->assignee->name;
    }

    private function ensureEnabled(): void
    {
        abort_unless(config('demo.errors_enabled'), 404);
    }
}
