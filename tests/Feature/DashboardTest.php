<?php

use App\Models\User;

test('dashboard is accessible to authenticated users', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get('/dashboard')
        ->assertOk();
});

test('dashboard redirects guests to login', function () {
    $this->get('/dashboard')
        ->assertRedirect('/login');
});
