<?php

use App\Models\User;

it('caps api login attempts per IP across different emails', function () {
    foreach (range(1, 20) as $i) {
        $this->postJson('/api/auth/login', ['email' => "orang$i@example.com", 'password' => 'salah-salah'])
            ->assertStatus(422);
    }

    $this->postJson('/api/auth/login', ['email' => 'orang-baru@example.com', 'password' => 'salah-salah'])
        ->assertStatus(429)
        ->assertHeader('Content-Type', 'application/json')
        ->assertJsonStructure(['message']);
});

it('shows an Indonesian 429 page on the web and JSON on the api', function () {
    foreach (range(1, 5) as $i) {
        $this->post('/login', ['email' => 'korban@example.com', 'password' => 'salah-salah']);
    }

    $this->post('/login', ['email' => 'korban@example.com', 'password' => 'salah-salah'])
        ->assertStatus(429)
        ->assertSee('Terlalu banyak percobaan. Silakan coba lagi dalam beberapa saat.');

    foreach (range(1, 5) as $i) {
        $this->postJson('/api/auth/login', ['email' => 'lain@example.com', 'password' => 'salah-salah']);
    }

    $this->postJson('/api/auth/login', ['email' => 'lain@example.com', 'password' => 'salah-salah'])
        ->assertStatus(429)
        ->assertHeader('Content-Type', 'application/json')
        ->assertDontSee('Terlalu banyak percobaan');
});

it('promotes and revokes admin with app:make-admin', function () {
    $user = makeUser(['email' => 'squatter@example.com']);

    $this->artisan('app:make-admin', ['email' => 'squatter@example.com'])->assertSuccessful();
    expect($user->fresh()->is_admin)->toBeTrue();

    $this->artisan('app:make-admin', ['email' => 'squatter@example.com', '--revoke' => true])->assertSuccessful();
    expect($user->fresh()->is_admin)->toBeFalse();

    $this->artisan('app:make-admin', ['email' => 'tidak-ada@example.com', '--revoke' => true])->assertFailed();
    expect(User::where('email', 'tidak-ada@example.com')->exists())->toBeFalse();
});
