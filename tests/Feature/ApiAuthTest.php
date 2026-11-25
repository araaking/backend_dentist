<?php

use App\Models\Consultation;
use App\Models\Patient;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Laravel\Sanctum\Sanctum;

it('returns 401 JSON (not a redirect) for guests on api routes', function () {
    foreach (['/api/patient', '/api/consultations', '/api/questions', '/api/auth/me'] as $uri) {
        // Tanpa header Accept: application/json sekalipun.
        $this->get($uri)
            ->assertStatus(401)
            ->assertHeader('Content-Type', 'application/json')
            ->assertJson(['message' => 'Unauthenticated.']);
    }
});

it('renders api 404s as JSON', function () {
    Sanctum::actingAs(makeUser());

    $this->get('/api/consultations/999999')
        ->assertNotFound()
        ->assertHeader('Content-Type', 'application/json');
});

it('throttles api login after 5 attempts per email and ip with a JSON 429', function () {
    $payload = ['email' => 'korban@example.com', 'password' => 'salah-salah'];

    foreach (range(1, 5) as $i) {
        $this->postJson('/api/auth/login', $payload)->assertStatus(422);
    }

    $this->postJson('/api/auth/login', $payload)
        ->assertStatus(429)
        ->assertJsonStructure(['message'])
        ->assertHeader('Retry-After');

    // Email lain tidak ikut terblokir.
    $this->postJson('/api/auth/login', ['email' => 'lain@example.com', 'password' => 'x'])->assertStatus(422);
});

it('throttles web login after 5 attempts', function () {
    foreach (range(1, 5) as $i) {
        $this->post('/login', ['email' => 'korban@example.com', 'password' => 'salah-salah'])->assertRedirect();
    }

    $this->post('/login', ['email' => 'korban@example.com', 'password' => 'salah-salah'])->assertStatus(429);
});

it('logs in with valid credentials and returns the original response keys', function () {
    makeUser(['email' => 'user@example.com']);

    $this->postJson('/api/auth/login', ['email' => 'user@example.com', 'password' => 'password123'])
        ->assertOk()
        ->assertJsonStructure(['user' => ['id', 'email'], 'token', 'token_type'])
        ->assertJson(['token_type' => 'Bearer']);
});

it('registers via api without the optional fields (old app behaviour)', function () {
    $this->postJson('/api/auth/register', [
        'name' => 'Budi',
        'email' => 'budi@example.com',
        'password' => 'password123',
    ])
        ->assertCreated()
        ->assertJsonStructure(['user', 'patient' => ['id', 'name'], 'token', 'token_type'])
        ->assertJsonPath('patient.name', 'Budi')
        ->assertJsonPath('patient.date_of_birth', null)
        ->assertJsonPath('patient.gender', null);

    $user = User::where('email', 'budi@example.com')->firstOrFail();
    expect($user->patient->user_id)->toBe($user->id);
    expect($user->is_admin)->toBeFalse();
});

it('registers via api with optional profile fields', function () {
    $this->postJson('/api/auth/register', [
        'name' => 'Siti',
        'email' => 'siti@example.com',
        'password' => 'password123',
        'date_of_birth' => '1995-03-20',
        'gender' => 'Female',
        'phone_number' => '08123456789',
    ])
        ->assertCreated()
        ->assertJsonPath('patient.gender', 'Female')
        ->assertJsonPath('patient.phone_number', '08123456789');

    $patient = Patient::where('name', 'Siti')->firstOrFail();
    expect($patient->date_of_birth)->toBe('1995-03-20');
});

it('validates the optional register fields', function () {
    $base = ['name' => 'X', 'email' => 'x@example.com', 'password' => 'password123'];

    $this->postJson('/api/auth/register', $base + ['gender' => 'Other'])->assertStatus(422)->assertJsonValidationErrors('gender');
    $this->postJson('/api/auth/register', $base + ['phone_number' => str_repeat('1', 16)])->assertStatus(422)->assertJsonValidationErrors('phone_number');
    $this->postJson('/api/auth/register', $base + ['date_of_birth' => 'bukan-tanggal'])->assertStatus(422)->assertJsonValidationErrors('date_of_birth');

    expect(User::count())->toBe(0);
});

it('does not create an orphan user when patient creation fails', function () {
    // Transaksi: bila pembuatan patient gagal, user ikut dibatalkan.
    Patient::creating(fn () => throw new RuntimeException('boom'));

    $this->postJson('/api/auth/register', [
        'name' => 'Gagal',
        'email' => 'gagal@example.com',
        'password' => 'password123',
    ])->assertStatus(500);

    expect(User::where('email', 'gagal@example.com')->exists())->toBeFalse();
});

it('logs out from all devices', function () {
    $user = makeUser();
    $token = $user->createToken('perangkat-1')->plainTextToken;
    $user->createToken('perangkat-2');
    expect($user->tokens()->count())->toBe(2);

    $this->withHeader('Authorization', 'Bearer '.$token)
        ->postJson('/api/auth/logout-all')
        ->assertOk();

    expect($user->tokens()->count())->toBe(0);
});

it('rejects expired tokens', function () {
    $user = makeUser();
    $token = $user->createToken('lama');
    $token->accessToken->forceFill(['created_at' => now()->subMinutes(config('sanctum.expiration') + 5)])->save();

    $this->withHeader('Authorization', 'Bearer '.$token->plainTextToken)
        ->getJson('/api/auth/me')
        ->assertUnauthorized();
});

it('configures a 30 day token expiration by default', function () {
    expect(config('sanctum.expiration'))->toBe(43200);
});

it('deletes the account with the correct password including photos and tokens', function () {
    Storage::fake('local');

    $user = makeUser(['email' => 'hapus@example.com']);
    $patientId = $user->patient->id;
    $consultation = makeConsultation($user->patient, true);
    $path = json_decode($consultation->answers()->where('question_code', 'E2')->firstOrFail()->answer, true)['photo_path'];
    Storage::disk('local')->assertExists($path);

    $token = $user->createToken('t')->plainTextToken;

    $this->withHeader('Authorization', 'Bearer '.$token)
        ->deleteJson('/api/account', ['password' => 'password123'])
        ->assertOk();

    expect(User::where('email', 'hapus@example.com')->exists())->toBeFalse();
    $this->assertDatabaseMissing('patients', ['id' => $patientId]);
    $this->assertDatabaseMissing('consultations', ['id' => $consultation->id]);
    $this->assertDatabaseMissing('answers', ['consultation_id' => $consultation->id]);
    $this->assertDatabaseMissing('diagnoses', ['consultation_id' => $consultation->id]);
    $this->assertDatabaseCount('personal_access_tokens', 0);
    Storage::disk('local')->assertMissing($path);
});

it('refuses account deletion with a wrong or missing password', function () {
    $user = makeUser();
    Sanctum::actingAs($user);

    $this->deleteJson('/api/account', ['password' => 'salah'])
        ->assertStatus(422)
        ->assertJsonValidationErrors('password');

    $this->deleteJson('/api/account')->assertStatus(422)->assertJsonValidationErrors('password');

    expect(User::find($user->id))->not->toBeNull();
});

it('deletes the web account after password confirmation and logs out', function () {
    Storage::fake('local');

    $user = makeUser();
    $consultation = makeConsultation($user->patient, true);

    $this->actingAs($user)
        ->delete(route('account.destroy'), ['password' => 'salah'])
        ->assertSessionHasErrors('password');
    expect(User::find($user->id))->not->toBeNull();

    $this->actingAs($user)
        ->delete(route('account.destroy'), ['password' => 'password123'])
        ->assertRedirect(route('login'));

    expect(User::find($user->id))->toBeNull();
    expect(Consultation::find($consultation->id))->toBeNull();
    $this->assertGuest();
});
