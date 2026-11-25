<?php

it('renders the dashboard for a user without a patient profile', function () {
    $user = makeUser([], false);

    $this->actingAs($user)
        ->get('/dashboard')
        ->assertOk()
        ->assertSee(route('patients.create'));
});

it('redirects consultation pages to patient creation when there is no patient', function () {
    $this->actingAs(makeUser([], false));

    $this->get('/consultations')->assertRedirect(route('patients.create'));
    $this->get('/consultations/create')->assertRedirect(route('patients.create'));
    $this->post('/consultations', consultationPayload())->assertRedirect(route('patients.create'));
});

it('creates a patient profile and redirects to the dashboard', function () {
    $user = makeUser([], false);

    $this->actingAs($user)
        ->post(route('patients.store'), ['name' => 'Budi', 'gender' => 'Male', 'user_id' => 999])
        ->assertRedirect(route('dashboard'));

    expect($user->fresh()->patient)->not->toBeNull();
    expect($user->fresh()->patient->user_id)->toBe($user->id);
});

it('refuses to create a second patient profile', function () {
    $user = makeUser();

    $this->actingAs($user)
        ->post(route('patients.store'), ['name' => 'Kedua'])
        ->assertRedirect(route('dashboard'))
        ->assertSessionHas('error');

    expect($user->patient()->count())->toBe(1);
});

it('cannot change user_id or patient_id through web patient update', function () {
    $user = makeUser();
    $other = makeUser();
    $patient = $user->patient;

    $this->actingAs($user)
        ->put(route('patients.update', $patient), [
            'name' => 'Nama Baru',
            'gender' => 'Female',
            'user_id' => $other->id,
            'patient_id' => 'HACK',
        ])
        ->assertRedirect(route('dashboard'));

    $patient->refresh();
    expect($patient->name)->toBe('Nama Baru');
    expect($patient->gender)->toBe('Female');
    expect($patient->user_id)->toBe($user->id);
    expect($patient->patient_id)->toBeNull();
});

it('forbids editing another user patient', function () {
    $user = makeUser();
    $other = makeUser();

    $this->actingAs($user)->get(route('patients.edit', $other->patient))->assertForbidden();
    $this->actingAs($user)->put(route('patients.update', $other->patient), ['name' => 'x'])->assertForbidden();
});

it('does not expose user_id/patient_id as fillable', function () {
    $fillable = (new \App\Models\Patient())->getFillable();

    expect($fillable)->not->toContain('user_id')->not->toContain('patient_id');
});

it('shows the delete-account section on the profile edit page', function () {
    $user = makeUser();

    $this->actingAs($user)
        ->get(route('patients.edit', $user->patient))
        ->assertOk()
        ->assertSee('Hapus Akun');
});
