<?php

use App\Models\Answer;
use App\Models\Consultation;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Laravel\Sanctum\Sanctum;

beforeEach(function () {
    Storage::fake('local');
    Storage::fake('public');
});

function photoPathOf(Consultation $consultation): ?string
{
    $answer = Answer::where('consultation_id', $consultation->id)->where('question_code', 'E2')->first();

    return $answer ? (json_decode($answer->answer, true)['photo_path'] ?? null) : null;
}

it('stores the base64 photo on the private local disk with a uuid name, not on public', function () {
    $user = makeUser();
    Sanctum::actingAs($user);

    $response = $this->postJson('/api/consultations', consultationPayload(['e2_photo' => 'data:image/png;base64,'.tinyPngBase64()]))
        ->assertCreated();

    $consultation = Consultation::findOrFail($response->json('consultation_id'));
    $path = photoPathOf($consultation);

    expect($path)->toMatch('#^consultation_photos/[0-9a-f-]{36}\.png$#');
    Storage::disk('local')->assertExists($path);
    Storage::disk('public')->assertMissing($path);

    expect($response->json('e2_photo_url'))->toBe(route('api.consultations.photo', $consultation));
});

it('stores multipart photos through the same service on the local disk', function () {
    Sanctum::actingAs(makeUser());

    $file = UploadedFile::fake()->createWithContent('selfie.png', tinyPngBinary());

    $response = $this->post('/api/consultations', array_merge(consultationPayload(), ['e2_photo_file' => $file]), ['Accept' => 'application/json'])
        ->assertCreated();

    $path = photoPathOf(Consultation::findOrFail($response->json('consultation_id')));

    expect($path)->toMatch('#\.png$#');
    Storage::disk('local')->assertExists($path);
    Storage::disk('public')->assertMissing($path);
});

it('rejects multipart files that are not real images', function () {
    Sanctum::actingAs(makeUser());

    $file = UploadedFile::fake()->createWithContent('evil.png', 'not an image at all');

    $this->post('/api/consultations', array_merge(consultationPayload(), ['e2_photo_file' => $file]), ['Accept' => 'application/json'])
        ->assertStatus(422)
        ->assertJsonValidationErrors('e2_photo_file');
});

it('returns 422 for invalid base64 or non-image payloads', function () {
    Sanctum::actingAs(makeUser());

    $this->postJson('/api/consultations', consultationPayload(['e2_photo' => 'ini bukan base64 !!!']))
        ->assertStatus(422)
        ->assertJsonValidationErrors('e2_photo');

    $this->postJson('/api/consultations', consultationPayload(['e2_photo' => base64_encode('hanya teks biasa')]))
        ->assertStatus(422)
        ->assertJsonValidationErrors('e2_photo');

    // GIF valid tetapi bukan JPEG/PNG.
    $gif = base64_encode(base64_decode('R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7'));
    $this->postJson('/api/consultations', consultationPayload(['e2_photo' => $gif]))
        ->assertStatus(422)
        ->assertJsonValidationErrors('e2_photo');

    expect(Consultation::count())->toBe(0);
    expect(Storage::disk('local')->allFiles())->toBe([]);
});

it('rejects base64 strings longer than the maximum length', function () {
    Sanctum::actingAs(makeUser());

    $this->postJson('/api/consultations', consultationPayload(['e2_photo' => str_repeat('A', 7000001)]))
        ->assertStatus(422)
        ->assertJsonValidationErrors('e2_photo');
});

it('rejects decoded images larger than 5 MB', function () {
    Sanctum::actingAs(makeUser());

    // PNG valid + padding sehingga > 5 MB setelah decode (tetap <= 7.000.000 karakter base64).
    $big = tinyPngBinary().str_repeat("\0", 5 * 1024 * 1024 + 1);

    $this->postJson('/api/consultations', consultationPayload(['e2_photo' => base64_encode($big)]))
        ->assertStatus(422)
        ->assertJsonValidationErrors('e2_photo');
});

it('does not trust photo_path supplied by the web user', function () {
    $user = makeUser();

    $this->actingAs($user)
        ->post('/consultations', consultationPayload([
            'e2_data' => ['photo_path' => 'consultation_photos/hack.jpg', 'opening_mm' => 10],
            'eq' => ['E2' => ['photo_path' => 'consultation_photos/hack2.jpg']],
        ]))
        ->assertRedirect();

    $consultation = Consultation::firstOrFail();
    $e2 = json_decode($consultation->answers()->where('question_code', 'E2')->firstOrFail()->answer, true);

    expect($e2['photo_path'])->toBeNull();
    expect($e2['opening_mm'])->toBe(40);
});

it('lets the owner fetch the photo via the web and api routes', function () {
    $user = makeUser();
    $consultation = makeConsultation($user->patient, true);

    $this->actingAs($user)
        ->get(route('consultations.photo', $consultation))
        ->assertOk()
        ->assertHeader('Content-Type', 'image/png');

    Sanctum::actingAs($user);
    $this->get(route('api.consultations.photo', $consultation))->assertOk();
});

it('forbids other users from fetching the photo', function () {
    $owner = makeUser();
    $other = makeUser();
    $consultation = makeConsultation($owner->patient, true);

    $this->actingAs($other)->get(route('consultations.photo', $consultation))->assertForbidden();

    Sanctum::actingAs($other);
    $this->getJson(route('api.consultations.photo', $consultation))->assertForbidden();
});

it('lets an admin fetch any web photo', function () {
    $consultation = makeConsultation(makeUser()->patient, true);

    $this->actingAs(makeAdmin())->get(route('consultations.photo', $consultation))->assertOk();
});

it('returns 401 for guests on the photo routes', function () {
    $consultation = makeConsultation(makeUser()->patient, true);

    $this->getJson(route('api.consultations.photo', $consultation))
        ->assertUnauthorized()
        ->assertJson(['message' => 'Unauthenticated.']);

    $this->get(route('consultations.photo', $consultation))->assertRedirect(route('login'));
});

it('returns 404 when the consultation has no photo or the stored path is unsafe', function () {
    $user = makeUser();
    $noPhoto = makeConsultation($user->patient);

    $this->actingAs($user)->get(route('consultations.photo', $noPhoto))->assertNotFound();

    // Data lama dengan path buatan pengguna tidak boleh dilayani.
    $noPhoto->answers()->where('question_code', 'E2')->update([
        'answer' => json_encode(['opening_mm' => 40, 'photo_path' => '../../.env']),
    ]);

    $this->actingAs($user)->get(route('consultations.photo', $noPhoto->fresh()))->assertNotFound();
});

it('adds e2_photo_url to api index and show without removing existing keys', function () {
    $user = makeUser();
    $withPhoto = makeConsultation($user->patient, true);
    $without = makeConsultation($user->patient);

    Sanctum::actingAs($user);

    $index = $this->getJson('/api/consultations')->assertOk();
    $items = collect($index->json('consultations'))->keyBy('id');

    expect($items[$withPhoto->id]['e2_photo_url'])->toBe(route('api.consultations.photo', $withPhoto));
    expect($items[$without->id]['e2_photo_url'])->toBeNull();
    expect($items[$withPhoto->id])->toHaveKeys(['id', 'patient_id', 'consultation_date', 'diagnoses'])->not->toHaveKey('answers');

    $show = $this->getJson('/api/consultations/'.$withPhoto->id)->assertOk();
    expect($show->json('consultation.e2_photo_url'))->toBe(route('api.consultations.photo', $withPhoto));
    expect($show->json('consultation'))->toHaveKeys(['answers', 'diagnoses']);
});

it('migrates legacy public photos to the local disk idempotently', function () {
    Storage::disk('public')->put('consultation_photos/lama.jpg', 'isi-foto');

    $this->artisan('app:migrate-consultation-photos')->assertSuccessful();

    Storage::disk('local')->assertExists('consultation_photos/lama.jpg');
    Storage::disk('public')->assertMissing('consultation_photos/lama.jpg');

    // Jalankan lagi: tidak ada yang perlu dipindahkan, tetap sukses.
    $this->artisan('app:migrate-consultation-photos')->assertSuccessful();
    expect(Storage::disk('local')->get('consultation_photos/lama.jpg'))->toBe('isi-foto');
});
