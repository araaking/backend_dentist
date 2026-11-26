<?php

use App\Models\Answer;
use App\Models\Consultation;
use App\Models\Diagnosis;
use App\Models\Question;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Support\Facades\Storage;
use Laravel\Sanctum\Sanctum;

beforeEach(function () {
    Storage::fake('local');
    Storage::fake('public');
});

it('ignores unknown SQ/EQ keys and never stores overly long codes', function () {
    $user = makeUser();
    Sanctum::actingAs($user);

    $payload = consultationPayload();
    $payload['sq']['SQ99'] = 'Ya';
    $payload['sq'][str_repeat('X', 300)] = 'Ya';
    $payload['eq']['E9'] = 'apa saja';
    $payload['eq']['E1']['Dagu'] = 3;

    $response = $this->postJson('/api/consultations', $payload)->assertCreated();

    $codes = Answer::where('consultation_id', $response->json('consultation_id'))->pluck('question_code')->all();

    expect($codes)->not->toContain('SQ99')->not->toContain('E9');
    expect(collect($codes)->every(fn ($c) => strlen($c) <= 5))->toBeTrue();
    expect($codes)->toContain('SQ1', 'SQ14', 'E1', 'E2', 'E3', 'E4');

    $e1 = json_decode(Answer::where('consultation_id', $response->json('consultation_id'))->where('question_code', 'E1')->first()->answer, true);
    expect($e1)->toBe(['Temporalis' => 0, 'Masseter' => 0, 'TMJ' => 0]);
});

it('stores the answer type (SQ/EQ) and exposes the decoded value attribute', function () {
    $user = makeUser();
    Sanctum::actingAs($user);

    $id = $this->postJson('/api/consultations', consultationPayload())->assertCreated()->json('consultation_id');

    $answers = Answer::where('consultation_id', $id)->get()->keyBy('question_code');

    expect($answers['SQ1']->type)->toBe('SQ');
    expect($answers['E1']->type)->toBe('EQ');
    expect($answers['E3']->type)->toBe('EQ');

    $show = $this->getJson("/api/consultations/$id")->assertOk();
    $byCode = collect($show->json('consultation.answers'))->keyBy('question_code');

    expect($byCode['SQ1']['answer'])->toBe('Tidak');
    expect($byCode['SQ1']['value'])->toBe('Tidak');
    expect($byCode['E1']['value'])->toBe(['Temporalis' => 0, 'Masseter' => 0, 'TMJ' => 0]);
    expect($byCode['E2']['value'])->toMatchArray(['opening_mm' => 40, 'photo_path' => null]);
});

it('rejects invalid SQ option values and out of range scores with 422', function () {
    Sanctum::actingAs(makeUser());

    $bad = consultationPayload();
    $bad['sq']['SQ1'] = 'Mungkin';
    $this->postJson('/api/consultations', $bad)->assertStatus(422)->assertJsonValidationErrors('sq.SQ1');

    $bad = consultationPayload();
    $bad['sq']['SQ3'] = 'Ya'; // nilai lama SQ3 sudah diganti Persisten/Kambuhan
    $this->postJson('/api/consultations', $bad)->assertStatus(422)->assertJsonValidationErrors('sq.SQ3');

    $bad = consultationPayload();
    $bad['eq']['E1']['TMJ'] = 7;
    $this->postJson('/api/consultations', $bad)->assertStatus(422)->assertJsonValidationErrors('eq.E1.TMJ');

    $bad = consultationPayload();
    $bad['eq']['E2']['opening_mm'] = 500;
    $this->postJson('/api/consultations', $bad)->assertStatus(422)->assertJsonValidationErrors('eq.E2.opening_mm');

    $bad = consultationPayload();
    $bad['eq']['E3'] = 'Ngik';
    $this->postJson('/api/consultations', $bad)->assertStatus(422)->assertJsonValidationErrors('eq.E3');

    $this->postJson('/api/consultations', [])->assertStatus(422)->assertJsonValidationErrors(['sq', 'eq']);
});

it('keeps the original store response keys and diagnoses a consultation', function () {
    Sanctum::actingAs(makeUser());

    $payload = consultationPayload(['sq' => ['SQ1' => 'Ya', 'SQ2' => '>= 1 minggu', 'SQ3' => 'Kambuhan (hilang timbul)', 'SQ4' => 'Ya'], 'eq' => ['E1' => ['Masseter' => 2], 'E4' => ['Masseter' => 2]]]);

    $this->postJson('/api/consultations', $payload)
        ->assertCreated()
        ->assertJsonStructure(['message', 'consultation_id', 'diagnoses'])
        ->assertJsonPath('message', 'Konsultasi berhasil dibuat.')
        ->assertJsonPath('diagnoses', ['Myalgia']);
});

it('stores the fallback diagnosis when no criteria are met', function () {
    Sanctum::actingAs(makeUser());

    $this->postJson('/api/consultations', consultationPayload(['sq' => ['SQ9' => 'Ya']]))
        ->assertCreated()
        ->assertJsonPath('diagnoses', ['No specific TMD diagnosis found.']);
});

it('never gives Acute closed lock when opening_mm is empty', function () {
    Sanctum::actingAs(makeUser());

    $payload = consultationPayload(['sq' => ['SQ9' => 'Ya', 'SQ10' => 'Ya', 'SQ12' => 'Ya'], 'eq' => ['E2' => ['opening_mm' => '']]]);

    $this->postJson('/api/consultations', $payload)
        ->assertCreated()
        ->assertJsonPath('diagnoses', ['Other Disc-based TMD']);
});

it('stores and returns several diagnoses in the fixed order', function () {
    Sanctum::actingAs(makeUser());

    $payload = consultationPayload([
        'sq' => ['SQ1' => 'Ya', 'SQ4' => 'Ya', 'SQ5' => 'Ya', 'SQ9' => 'Ya', 'SQ10' => 'Ya', 'SQ12' => 'Ya', 'SQ13' => 'Ya', 'SQ14' => 'Ya'],
        'eq' => ['E1' => ['TMJ' => 1], 'E2' => ['opening_mm' => 30], 'E4' => ['Temporalis' => 1, 'Masseter' => 1, 'TMJ' => 1]],
    ]);
    $expected = ['Myalgia', 'Arthralgia', 'Headache attributed to TMD (HA-TMD)', 'Acute closed lock', 'Subluxation'];

    $id = $this->postJson('/api/consultations', $payload)
        ->assertCreated()
        ->assertJsonPath('diagnoses', $expected)
        ->json('consultation_id');

    expect(Consultation::with('diagnoses')->findOrFail($id)->diagnoses->pluck('name')->all())->toBe($expected);

    $this->getJson("/api/consultations/$id")
        ->assertOk()
        ->assertJsonPath('consultation.diagnoses.*.name', $expected);

    $this->getJson('/api/consultations')
        ->assertOk()
        ->assertJsonPath('consultations.0.diagnoses.*.name', $expected);
});

it('shows several diagnoses with descriptions on the web result page', function () {
    $user = makeUser();
    $consultation = makeConsultation($user->patient, false, [
        'sq' => ['SQ1' => 'Ya', 'SQ4' => 'Ya', 'SQ13' => 'Ya', 'SQ14' => 'Ya'],
        'eq' => ['E1' => ['Masseter' => 1], 'E4' => ['Masseter' => 1, 'TMJ' => 1], 'E3' => 'Kliking'],
    ]);

    $response = $this->actingAs($user)->get(route('consultations.show', $consultation))->assertOk();

    foreach (['Myalgia', 'Arthralgia', 'Other Disc-based TMD', 'Subluxation'] as $name) {
        $response->assertSee($name)->assertSee(Diagnosis::DESCRIPTIONS[$name]);
    }
    $response->assertDontSee('Tidak ada deskripsi.')->assertDontSee('tidak ditemukan diagnosis spesifik');
});

it('shows the info box on the web result page when there is no diagnosis', function () {
    $user = makeUser();
    $consultation = makeConsultation($user->patient);

    $this->actingAs($user)->get(route('consultations.show', $consultation))
        ->assertOk()
        ->assertSee('tidak ditemukan diagnosis spesifik');
});

it('stores a web consultation through the shared validation and service', function () {
    $user = makeUser();

    $this->actingAs($user)
        ->post('/consultations', consultationPayload([
            'sq' => ['SQ9' => 'Ya', 'SQ10' => 'Ya'],
            'e2_photo' => 'data:image/png;base64,'.tinyPngBase64(),
        ]))
        ->assertRedirect();

    $consultation = Consultation::with('diagnoses')->firstOrFail();

    expect($consultation->diagnoses->pluck('name')->all())->toBe(['Other Disc-based TMD']);
    expect($consultation->photoPath())->not->toBeNull();
    Storage::disk('local')->assertExists($consultation->photoPath());
});

it('web consultation with an invalid photo returns validation errors, not a 500', function () {
    $this->actingAs(makeUser())
        ->post('/consultations', consultationPayload(['e2_photo' => 'ngawur!!']))
        ->assertSessionHasErrors('e2_photo');

    expect(Consultation::count())->toBe(0);
});

it('renders the web consultation form from the shared questionnaire', function () {
    $this->actingAs(makeUser())
        ->get('/consultations/create')
        ->assertOk()
        ->assertSee('name="sq[SQ1]"', false)
        ->assertSee('name="sq[SQ14]"', false)
        ->assertSee('name="eq[E1][TMJ]"', false)
        ->assertSee('name="eq[E4][Temporalis]"', false)
        ->assertSee('name="eq[E2][opening_mm]"', false)
        ->assertSee('name="eq[E3]"', false);
});

it('shows the consultation history and detail to the owner', function () {
    $user = makeUser();
    $consultation = makeConsultation($user->patient, true);

    $this->actingAs($user)
        ->get('/consultations')
        ->assertOk()
        ->assertSee(route('consultations.photo', $consultation));

    $this->actingAs($user)
        ->get(route('consultations.show', $consultation))
        ->assertOk()
        ->assertSee(route('consultations.photo', $consultation))
        ->assertSee('40 mm');
});

it('forbids viewing another user consultation on the web', function () {
    $consultation = makeConsultation(makeUser()->patient);

    $this->actingAs(makeUser())->get(route('consultations.show', $consultation))->assertForbidden();
});

it('returns the same questions JSON structure as the legacy api', function () {
    Sanctum::actingAs(makeUser());

    $json = $this->getJson('/api/questions')->assertOk()->json();

    expect(array_keys($json))->toBe(['sq', 'eq']);
    expect($json['sq'])->toHaveCount(14);
    expect(collect($json['sq'])->pluck('code')->all())->toBe(array_map(fn ($i) => "SQ$i", range(1, 14)));

    expect($json['sq'][0])->toBe([
        'code' => 'SQ1',
        'text' => 'Apakah Anda merasakan nyeri di rahang, pelipis, atau telinga?',
        'input' => ['type' => 'radio', 'options' => ['Ya', 'Tidak'], 'default' => 'Tidak'],
    ]);
    expect($json['sq'][1]['input'])->toBe(['type' => 'radio', 'options' => ['< 1 minggu', '>= 1 minggu'], 'default' => '< 1 minggu']);
    expect($json['sq'][2]['input'])->toBe(['type' => 'radio', 'options' => ['Persisten (terus-menerus)', 'Kambuhan (hilang timbul)', 'Tidak'], 'default' => 'Tidak']);
    expect($json['sq'][5]['input']['options'])->toBe(['< 1x/minggu', '>= 1x/minggu', 'Tidak']);
    expect($json['sq'][7]['input'])->toBe(['type' => 'select', 'options' => ['Tidak ada', 'Sesekali', 'Sering'], 'default' => 'Tidak ada']);

    expect(collect($json['eq'])->pluck('code')->all())->toBe(['E1', 'E2', 'E3', 'E4']);

    [$e1, $e2, $e3, $e4] = $json['eq'];

    expect(array_keys($e1))->toBe(['code', 'text', 'input', 'assets']);
    expect($e1['input'])->toBe([
        'type' => 'group_select_scores',
        'areas' => ['Temporalis', 'Masseter', 'TMJ'],
        'min' => 0,
        'max' => 3,
        'labels' => ['0 - Tidak Nyeri', '1 - Nyeri Ringan', '2 - Nyeri Sedang', '3 - Nyeri Berat'],
    ]);
    expect($e1['assets'])->toBe(['image' => asset('images/e1.png')]);

    expect($e2['input']['type'])->toBe('composite');
    expect($e2['input']['fields'][0])->toBe([
        'name' => 'opening_mm', 'type' => 'number', 'placeholder' => 'Contoh: 40', 'required' => true, 'unit' => 'mm', 'min' => 0,
    ]);
    expect($e2['input']['photo'])->toBe([
        'accepted' => ['base64', 'multipart'],
        'base64_field' => 'e2_photo',
        'multipart_field' => 'e2_photo_file',
        'accept_mime' => ['image/jpeg', 'image/png'],
        'max_size_mb' => 5,
    ]);

    expect($e3['input']['default'])->toBe('Tidak ada');
    expect($e3['input']['options'][0])->toBe(['value' => 'Tidak ada', 'label' => 'Tidak ada bunyi']);
    expect($e3['input']['options'][1])->toBe([
        'value' => 'Kliking', 'label' => 'Bunyi kliking', 'audio' => asset('sounds/kliktunggaldanganda.wav'),
    ]);
    expect($e3['input']['options'][2])->toBe([
        'value' => 'Krepitasi', 'label' => 'Bunyi kasar seperti pasir (krepitasi)', 'audio' => asset('sounds/krepitasi.wav'),
    ]);

    expect(array_keys($e4))->toBe(['code', 'text', 'input']);
    expect($e4['input']['areas'])->toBe(['Temporalis', 'Masseter', 'TMJ']);
});

it('seeds the questions table and a test user in the testing environment', function () {
    $this->seed(DatabaseSeeder::class);

    expect(Question::count())->toBe(18);
    expect(Question::where('code', 'SQ1')->first()->type)->toBe('Subjective');
    expect(Question::where('code', 'E1')->first()->type)->toBe('Objective');

    $this->assertDatabaseHas('users', ['email' => 'test@example.com', 'is_admin' => false]);

    // Idempotent.
    $this->seed(DatabaseSeeder::class);
    expect(Question::count())->toBe(18);
});

it('admin dashboard shows question stats and answer distribution by type after seeding', function () {
    $this->seed(DatabaseSeeder::class);

    $user = makeUser();
    makeConsultation($user->patient);

    $response = $this->actingAs(makeAdmin())->get('/admin/dashboard')->assertOk();

    $distribution = $response->viewData('answerDistributionByType')->pluck('answer_count', 'type');
    expect($distribution['SQ'])->toBe(14);
    expect($distribution['EQ'])->toBe(4);
    expect($response->viewData('mostAnsweredQuestions'))->not->toBeEmpty();
});

it('makes a user admin with app:make-admin and can create one', function () {
    $user = makeUser(['email' => 'calon@example.com']);

    $this->artisan('app:make-admin', ['email' => 'calon@example.com'])->assertSuccessful();
    expect($user->fresh()->is_admin)->toBeTrue();

    $this->artisan('app:make-admin', ['email' => 'baru@example.com'])->assertFailed();

    $this->artisan('app:make-admin', ['email' => 'baru@example.com', '--create' => true, '--password' => 'rahasia-123'])->assertSuccessful();
    $created = User::where('email', 'baru@example.com')->firstOrFail();
    expect($created->is_admin)->toBeTrue();
    expect($created->patient)->not->toBeNull();
});
