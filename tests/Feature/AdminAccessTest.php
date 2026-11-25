<?php

use App\Models\User;

it('does not grant admin to whoever registers admin@dentist.com', function () {
    $this->post('/register', [
        'name' => 'Penyusup',
        'email' => 'admin@dentist.com',
        'password' => 'password123',
        'password_confirmation' => 'password123',
        'date_of_birth' => '1990-01-01',
        'gender' => 'Male',
        'phone_number' => '08123456789',
    ])->assertRedirect(route('dashboard'));

    $user = User::where('email', 'admin@dentist.com')->firstOrFail();
    expect($user->is_admin)->toBeFalse();

    $this->get('/admin/dashboard')->assertRedirect(route('dashboard'));
});

it('redirects non-admin users away from all admin pages', function () {
    $user = makeUser();
    $patient = $user->patient;

    $this->actingAs($user);

    $this->get('/admin/dashboard')->assertRedirect(route('dashboard'))->assertSessionHas('error');
    $this->get('/admin/patients')->assertRedirect(route('dashboard'));
    $this->get('/admin/patients/'.$patient->id)->assertRedirect(route('dashboard'));
});

it('redirects guests from admin pages to login', function () {
    $this->get('/admin/dashboard')->assertRedirect(route('login'));
});

it('lets users flagged is_admin into the admin pages', function () {
    $admin = makeAdmin(['email' => 'boss@example.com']);
    $patient = makeUser()->patient;

    $this->actingAs($admin);

    $this->get('/admin/dashboard')->assertOk();
    $this->get('/admin/patients')->assertOk();
    $this->get('/admin/patients/'.$patient->id)->assertOk();
});

it('does not allow mass assigning is_admin', function () {
    $user = User::create(['email' => 'x@example.com', 'password' => 'password123', 'is_admin' => true]);

    expect($user->fresh()->is_admin)->toBeFalse();
});

it('shows the admin link on the dashboard only for admins', function () {
    $this->actingAs(makeUser());
    $this->get('/dashboard')->assertOk()->assertDontSee(route('admin.dashboard'));

    $this->actingAs(makeAdmin());
    $this->get('/dashboard')->assertOk()->assertSee(route('admin.dashboard'));
});

it('admin dashboard search filters the all-patients table', function () {
    $admin = makeAdmin();
    makeUser(['email' => 'budi@example.com'], true, ['name' => 'Budi Santoso', 'phone_number' => '0811111']);
    makeUser(['email' => 'siti@example.com'], true, ['name' => 'Siti Aminah', 'phone_number' => '0822222']);

    $this->actingAs($admin);

    $names = fn ($response) => $response->viewData('allPatients')->pluck('name')->all();

    expect($names($this->get('/admin/dashboard?search=Budi')->assertOk()))->toBe(['Budi Santoso']);
    expect($names($this->get('/admin/dashboard?search=0822222')))->toBe(['Siti Aminah']);
    expect($names($this->get('/admin/dashboard?search=siti@example')))->toBe(['Siti Aminah']);
    expect($names($this->get('/admin/dashboard?search=tidakada')))->toBe([]);
});

it('escapes LIKE wildcards in admin search', function () {
    $admin = makeAdmin();
    makeUser([], true, ['name' => 'Budi Santoso']);

    $this->actingAs($admin);

    $response = $this->get('/admin/patients?search='.urlencode('%'));
    expect($response->viewData('patients')->pluck('name')->all())->toBe([]);

    $response = $this->get('/admin/patients?search='.urlencode('B_di'));
    expect($response->viewData('patients')->pluck('name')->all())->toBe([]);
});

it('admin patients index filters by search and keeps the query string in pagination links', function () {
    $admin = makeAdmin();
    foreach (range(1, 12) as $i) {
        makeUser([], true, ['name' => "Pasien Uji $i"]);
    }
    makeUser([], true, ['name' => 'Orang Lain']);

    $this->actingAs($admin);

    $response = $this->get('/admin/patients?search='.urlencode('Pasien Uji'))->assertOk();

    expect($response->viewData('patients')->total())->toBe(12);
    $response->assertDontSee('Orang Lain');
    $response->assertSee('search=Pasien%20Uji', false);
});

it('shows gender and missing date of birth correctly on admin patient pages', function () {
    $admin = makeAdmin();
    $male = makeUser([], true, ['name' => 'Pasien Pria', 'gender' => 'Male', 'date_of_birth' => '1990-05-17']);
    $noDob = makeUser([], true, ['name' => 'Tanpa Tanggal', 'gender' => null]);

    $this->actingAs($admin);

    $this->get('/admin/patients/'.$male->patient->id)
        ->assertSee('Laki-laki')
        ->assertSee('17/05/1990');

    $this->get('/admin/patients/'.$noDob->patient->id)
        ->assertDontSee(now()->format('d/m/Y'));
});
