<?php

use App\Jobs\SendTicketCompletedNotification;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Queue;

test('demo error routes require an admin', function () {
    $manager = User::factory()->manager()->create();

    $this->actingAs($manager)
        ->post(route('demo.errors.exception'))
        ->assertForbidden();
});

test('unhandled exception demo returns a server error', function () {
    $admin = User::factory()->admin()->create();

    $this->actingAs($admin)
        ->post(route('demo.errors.exception'))
        ->assertStatus(500);
});

test('failed notification job demo dispatches a job', function () {
    Queue::fake();

    $admin = User::factory()->admin()->create();
    Ticket::factory()->create();

    $this->actingAs($admin)
        ->from(route('dashboard'))
        ->post(route('demo.errors.failed-job'))
        ->assertRedirect(route('dashboard'));

    Queue::assertPushed(SendTicketCompletedNotification::class);
});

test('failed webhook demo returns a server error', function () {
    Http::fake([
        'httpstat.us/*' => Http::response('Server Error', 500),
    ]);

    config(['demo.webhook_url' => 'https://httpstat.us/500']);

    $admin = User::factory()->admin()->create();
    Ticket::factory()->create(['assigned_to' => null]);

    $this->actingAs($admin)
        ->post(route('demo.errors.webhook'))
        ->assertStatus(500);
});

test('missing assignee demo returns a server error', function () {
    $admin = User::factory()->admin()->create();
    Ticket::factory()->create(['assigned_to' => null]);

    $this->actingAs($admin)
        ->post(route('demo.errors.missing-assignee'))
        ->assertStatus(500);
});
