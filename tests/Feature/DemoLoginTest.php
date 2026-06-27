<?php

use App\Models\User;

test('login screen shows the quick login dropdown', function () {
    $admin = User::factory()->admin()->create(['name' => 'John Admin']);
    $manager = User::factory()->manager()->create(['name' => 'Sarah Manager']);

    $this->get('/login')
        ->assertOk()
        ->assertSee('Quick login')
        ->assertSee('John Admin (Admin)')
        ->assertSee('Sarah Manager (Manager)');
});

test('guests can log in as a selected user', function () {
    $admin = User::factory()->admin()->create();
    $manager = User::factory()->manager()->create();

    $response = $this->post('/demo-login', [
        'user_id' => $manager->id,
    ]);

    $this->assertAuthenticatedAs($manager);
    $response->assertRedirect(route('dashboard', absolute: false));
});

test('demo login requires a valid user', function () {
    $this->post('/demo-login', [
        'user_id' => 999,
    ])->assertSessionHasErrors('user_id');

    $this->assertGuest();
});
