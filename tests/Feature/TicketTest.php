<?php

use App\Enums\TicketStatus;
use App\Models\Ticket;
use App\Models\User;

test('managers can view all tickets', function () {
    $manager = User::factory()->manager()->create();
    $assignedTicket = Ticket::factory()->create(['subject' => 'Assigned ticket']);
    $unassignedTicket = Ticket::factory()->create(['subject' => 'Unassigned ticket']);

    $this->actingAs($manager)
        ->get(route('tickets.index'))
        ->assertOk()
        ->assertSee('Assigned ticket')
        ->assertSee('Unassigned ticket');
});

test('advisors cannot view the tickets list', function () {
    $advisor = User::factory()->create();
    Ticket::factory()->assignedTo($advisor)->create(['subject' => 'My assigned ticket']);

    $this->actingAs($advisor)
        ->get(route('tickets.index'))
        ->assertForbidden();
});

test('managers can create tickets', function () {
    $manager = User::factory()->manager()->create();

    $response = $this->actingAs($manager)->post(route('tickets.store'), [
        'subject' => 'Need help with email',
        'message' => 'My inbox stopped syncing this morning.',
    ]);

    $ticket = Ticket::query()->where('subject', 'Need help with email')->first();

    expect($ticket)->not->toBeNull()
        ->and($ticket->status)->toBe(TicketStatus::Open)
        ->and($ticket->created_by)->toBe($manager->id);

    $response->assertRedirect(route('tickets.show', $ticket));
});

test('advisors cannot create tickets', function () {
    $advisor = User::factory()->create();

    $this->actingAs($advisor)
        ->get(route('tickets.create'))
        ->assertForbidden();
});

test('managers can assign tickets', function () {
    $manager = User::factory()->manager()->create();
    $advisor = User::factory()->create();
    $ticket = Ticket::factory()->create(['assigned_to' => null]);

    $this->actingAs($manager)
        ->put(route('tickets.update', $ticket), [
            'status' => TicketStatus::InProgress->value,
            'assigned_to' => $advisor->id,
        ])
        ->assertRedirect(route('tickets.show', $ticket));

    $ticket->refresh();

    expect($ticket->status)->toBe(TicketStatus::InProgress)
        ->and($ticket->assigned_to)->toBe($advisor->id);
});

test('advisors can accept the oldest unassigned ticket', function () {
    $advisor = User::factory()->create();

    $olderTicket = Ticket::factory()->create([
        'subject' => 'Older ticket',
        'assigned_to' => null,
        'status' => TicketStatus::Open,
        'created_at' => now()->subHour(),
    ]);

    Ticket::factory()->create([
        'subject' => 'Newer ticket',
        'assigned_to' => null,
        'status' => TicketStatus::Open,
        'created_at' => now(),
    ]);

    $this->actingAs($advisor)
        ->post(route('tickets.accept-next'))
        ->assertRedirect(route('tickets.show', $olderTicket));

    $olderTicket->refresh();

    expect($olderTicket->assigned_to)->toBe($advisor->id)
        ->and($olderTicket->status)->toBe(TicketStatus::InProgress);
});

test('accept next ticket redirects with a message when none are available', function () {
    $advisor = User::factory()->create();

    $this->actingAs($advisor)
        ->post(route('tickets.accept-next'))
        ->assertRedirect(route('dashboard'))
        ->assertSessionHas('status', 'No unassigned tickets are available.');
});

test('advisors can mark an assigned ticket as completed', function () {
    $advisor = User::factory()->create();
    $ticket = Ticket::factory()->assignedTo($advisor)->create([
        'status' => TicketStatus::InProgress,
    ]);

    $this->actingAs($advisor)
        ->post(route('tickets.complete', $ticket))
        ->assertRedirect(route('dashboard'))
        ->assertSessionHas('status', 'Ticket marked as completed.');

    expect($ticket->fresh()->status)->toBe(TicketStatus::Resolved);
});

test('advisors cannot complete a ticket they are not assigned to', function () {
    $advisor = User::factory()->create();
    $ticket = Ticket::factory()->create([
        'status' => TicketStatus::InProgress,
    ]);

    $this->actingAs($advisor)
        ->post(route('tickets.complete', $ticket))
        ->assertForbidden();
});

test('advisors cannot complete an already completed ticket', function () {
    $advisor = User::factory()->create();
    $ticket = Ticket::factory()->assignedTo($advisor)->create([
        'status' => TicketStatus::Resolved,
    ]);

    $this->actingAs($advisor)
        ->post(route('tickets.complete', $ticket))
        ->assertForbidden();
});

test('advisors cannot update tickets they are not assigned to', function () {
    $advisor = User::factory()->create();
    $ticket = Ticket::factory()->create(['assigned_to' => null]);

    $this->actingAs($advisor)
        ->put(route('tickets.update', $ticket), [
            'status' => TicketStatus::Resolved->value,
        ])
        ->assertForbidden();
});

test('managers cannot accept next ticket', function () {
    $manager = User::factory()->manager()->create();
    Ticket::factory()->create(['assigned_to' => null, 'status' => TicketStatus::Open]);

    $this->actingAs($manager)
        ->post(route('tickets.accept-next'))
        ->assertForbidden();
});
