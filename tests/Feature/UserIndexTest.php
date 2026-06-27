<?php

use App\Models\User;

test('admins can view the users list', function () {
    $admin = User::factory()->admin()->create();

    $this->actingAs($admin)
        ->get(route('users.index'))
        ->assertOk()
        ->assertSee('Users')
        ->assertSee($admin->email)
        ->assertSee('Admin');
});

test('non-admins cannot view the users list', function () {
    $manager = User::factory()->manager()->create();

    $this->actingAs($manager)
        ->get(route('users.index'))
        ->assertForbidden();
});

test('new registrations default to associate role', function () {
    $response = $this->post('/register', [
        'name' => 'New User',
        'email' => 'new@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
    ]);

    $response->assertRedirect(route('dashboard', absolute: false));

    $user = User::query()->where('email', 'new@example.com')->first();

    expect($user->role->value)->toBe('associate');
});
