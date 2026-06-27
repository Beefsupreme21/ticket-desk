<?php

namespace App\Http\Controllers;

use App\Enums\TicketStatus;
use App\Http\Requests\StoreTicketRequest;
use App\Http\Requests\UpdateTicketRequest;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class TicketController extends Controller
{
    public function index(Request $request): View
    {
        $this->authorize('viewAny', Ticket::class);

        $tickets = Ticket::query()
            ->with(['assignee', 'creator'])
            ->visibleTo($request->user())
            ->latest()
            ->get();

        return view('tickets.index', compact('tickets'));
    }

    public function create(): View
    {
        $this->authorize('create', Ticket::class);

        return view('tickets.create');
    }

    public function store(StoreTicketRequest $request): RedirectResponse
    {
        $ticket = Ticket::query()->create([
            ...$request->validated(),
            'status' => TicketStatus::Open,
            'created_by' => $request->user()->id,
        ]);

        return redirect()->route('tickets.show', $ticket);
    }

    public function show(Ticket $ticket): View
    {
        $this->authorize('view', $ticket);

        $ticket->load(['assignee', 'creator']);

        return view('tickets.show', compact('ticket'));
    }

    public function edit(Ticket $ticket): View
    {
        $this->authorize('update', $ticket);

        $ticket->load(['assignee', 'creator']);

        $assignees = User::query()
            ->orderBy('name')
            ->get();

        return view('tickets.edit', compact('ticket', 'assignees'));
    }

    public function update(UpdateTicketRequest $request, Ticket $ticket): RedirectResponse
    {
        $ticket->update($request->validated());

        return redirect()->route('tickets.show', $ticket);
    }

    public function complete(Ticket $ticket): RedirectResponse
    {
        $this->authorize('complete', $ticket);

        $ticket->update(['status' => TicketStatus::Resolved]);

        return redirect()->route('dashboard')
            ->with('status', __('Ticket marked as completed.'));
    }

    public function acceptNext(Request $request): RedirectResponse
    {
        $this->authorize('acceptNext', Ticket::class);

        $ticket = DB::transaction(function () use ($request): ?Ticket {
            $ticket = Ticket::query()
                ->unassigned()
                ->oldest()
                ->lockForUpdate()
                ->first();

            if ($ticket === null) {
                return null;
            }

            $ticket->update([
                'assigned_to' => $request->user()->id,
                'status' => TicketStatus::InProgress,
            ]);

            return $ticket;
        });

        if ($ticket === null) {
            return redirect()->route('dashboard')
                ->with('status', __('No unassigned tickets are available.'));
        }

        return redirect()->route('tickets.show', $ticket);
    }
}
