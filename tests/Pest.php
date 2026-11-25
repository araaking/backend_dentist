<?php

/*
|--------------------------------------------------------------------------
| Test Case
|--------------------------------------------------------------------------
|
| The closure you provide to your test functions is always bound to a specific PHPUnit test
| case class. By default, that class is "PHPUnit\Framework\TestCase". Of course, you may
| need to change it using the "pest()" function to bind a different classes or traits.
|
*/

pest()->extend(Tests\TestCase::class)
    ->use(Illuminate\Foundation\Testing\RefreshDatabase::class)
    ->in('Feature');

/*
|--------------------------------------------------------------------------
| Expectations
|--------------------------------------------------------------------------
|
| When you're writing tests, you often need to check that values meet certain conditions. The
| "expect()" function gives you access to a set of "expectations" methods that you can use
| to assert different things. Of course, you may extend the Expectation API at any time.
|
*/

expect()->extend('toBeOne', function () {
    return $this->toBe(1);
});

/*
|--------------------------------------------------------------------------
| Functions
|--------------------------------------------------------------------------
|
| While Pest is very powerful out-of-the-box, you may have some testing code specific to your
| project that you don't want to repeat in every file. Here you can also expose helpers as
| global functions to help you to reduce the number of lines of code in your test files.
|
*/

use App\Models\Consultation;
use App\Models\Patient;
use App\Models\User;
use App\Services\ConsultationRecorder;

/** PNG 1x1 valid, dalam base64. */
function tinyPngBase64(): string
{
    return 'iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNkYPhfDwAChwGA60e6kgAAAABJRU5ErkJggg==';
}

function tinyPngBinary(): string
{
    return base64_decode(tinyPngBase64());
}

/** Buat user (+ profil pasien) dengan password 'password123'. */
function makeUser(array $attributes = [], bool $withPatient = true, array $patient = []): User
{
    $user = User::create(array_merge([
        'email' => fake()->unique()->safeEmail(),
        'password' => 'password123',
    ], $attributes));

    if ($withPatient) {
        $user->patient()->create(array_merge(['name' => fake()->name()], $patient));
    }

    return $user->fresh();
}

function makeAdmin(array $attributes = []): User
{
    $user = makeUser($attributes);
    $user->forceFill(['is_admin' => true])->save();

    return $user->fresh();
}

/** Payload konsultasi yang valid (semua jawaban default). */
function consultationPayload(array $overrides = []): array
{
    $sq = [];
    foreach (\App\Support\TmdQuestionnaire::sq() as $code => $q) {
        $sq[$code] = $q['default'];
    }

    $zeros = ['Temporalis' => 0, 'Masseter' => 0, 'TMJ' => 0];

    return array_replace_recursive([
        'sq' => $sq,
        'eq' => [
            'E1' => $zeros,
            'E2' => ['opening_mm' => 40],
            'E3' => 'Tidak ada',
            'E4' => $zeros,
        ],
    ], $overrides);
}

/** Simpan konsultasi langsung lewat service (opsional dengan foto). */
function makeConsultation(Patient $patient, bool $withPhoto = false, array $overrides = []): Consultation
{
    return app(ConsultationRecorder::class)->record(
        $patient,
        consultationPayload($overrides),
        null,
        $withPhoto ? tinyPngBase64() : null,
    );
}