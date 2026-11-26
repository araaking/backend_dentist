<?php

use App\Services\TmdDiagnosisService as Tmd;

/**
 * Semua jawaban default negatif; $sq/$eq menimpa sebagian. Opening default 40 mm.
 */
function diag(array $sq = [], array $eq = []): array
{
    $defaultSq = [
        'SQ1' => 'Tidak', 'SQ2' => '< 1 minggu', 'SQ3' => 'Tidak', 'SQ4' => 'Tidak', 'SQ5' => 'Tidak',
        'SQ6' => 'Tidak', 'SQ7' => 'Tidak', 'SQ8' => 'Tidak ada', 'SQ9' => 'Tidak', 'SQ10' => 'Tidak',
        'SQ11' => 'Tidak', 'SQ12' => 'Tidak', 'SQ13' => 'Tidak', 'SQ14' => 'Tidak',
    ];
    $zeros = ['Temporalis' => 0, 'Masseter' => 0, 'TMJ' => 0];
    $defaultEq = ['E1' => $zeros, 'E2' => ['opening_mm' => 40], 'E3' => 'Tidak ada', 'E4' => $zeros];

    return (new Tmd)->diagnose(
        array_replace($defaultSq, $sq),
        array_replace_recursive($defaultEq, $eq),
    );
}

const NONE_RESULT = [Tmd::NONE];

// ---------------------------------------------------------------- Jalur 1: Painful TMDs

it('passes the history gate with each SQ1/SQ5 x SQ4/SQ7 combination', function (array $sq) {
    // E1 + E4 otot terpenuhi -> Myalgia bila history lolos.
    $eq = ['E1' => ['Masseter' => 1], 'E4' => ['Masseter' => 1]];
    expect(diag($sq, $eq))->toContain(Tmd::MYALGIA);
})->with([
    'SQ1 + SQ4' => [['SQ1' => 'Ya', 'SQ4' => 'Ya']],
    'SQ1 + SQ7' => [['SQ1' => 'Ya', 'SQ7' => 'Ya']],
    'SQ5 + SQ4' => [['SQ5' => 'Ya', 'SQ4' => 'Ya']],
    'SQ5 + SQ7' => [['SQ5' => 'Ya', 'SQ7' => 'Ya']],
]);

it('fails the history gate without (SQ1 or SQ5) and (SQ4 or SQ7)', function (array $sq) {
    $eq = ['E1' => ['Masseter' => 2, 'TMJ' => 2], 'E4' => ['Masseter' => 2, 'TMJ' => 2, 'Temporalis' => 2]];
    expect(diag($sq, $eq))->toBe(NONE_RESULT);
})->with([
    'tanpa jawaban' => [[]],
    'hanya SQ1' => [['SQ1' => 'Ya']],
    'hanya SQ5' => [['SQ5' => 'Ya']],
    'hanya SQ4' => [['SQ4' => 'Ya']],
    'hanya SQ7' => [['SQ7' => 'Ya']],
    'SQ4 + SQ7 tanpa nyeri' => [['SQ4' => 'Ya', 'SQ7' => 'Ya']],
    'SQ1 + SQ5 tanpa modifikasi rahang' => [['SQ1' => 'Ya', 'SQ5' => 'Ya']],
]);

it('requires E1 confirmation of pain location (E4 pain alone gives nothing)', function () {
    $sq = ['SQ1' => 'Ya', 'SQ4' => 'Ya'];
    expect(diag($sq, ['E4' => ['Masseter' => 3, 'TMJ' => 3, 'Temporalis' => 3]]))->toBe(NONE_RESULT);
});

it('accepts E1 in any area as location confirmation', function (string $area) {
    $sq = ['SQ1' => 'Ya', 'SQ4' => 'Ya'];
    // E1 di area lain dari E4: gerbang tetap lolos.
    expect(diag($sq, ['E1' => [$area => 1], 'E4' => ['Masseter' => 1]]))->toBe([Tmd::MYALGIA]);
})->with(['Temporalis', 'Masseter', 'TMJ']);

it('requires familiar pain on E4 palpation (E1 alone gives nothing)', function () {
    $sq = ['SQ1' => 'Ya', 'SQ4' => 'Ya'];
    expect(diag($sq, ['E1' => ['Masseter' => 3, 'TMJ' => 3, 'Temporalis' => 3]]))->toBe(NONE_RESULT);
});

it('diagnoses Myalgia from E4 temporalis or masseter pain', function () {
    $sq = ['SQ1' => 'Ya', 'SQ4' => 'Ya'];
    expect(diag($sq, ['E1' => ['TMJ' => 1], 'E4' => ['Masseter' => 1]]))->toBe([Tmd::MYALGIA]);
    expect(diag(['SQ1' => 'Ya', 'SQ7' => 'Ya'], ['E1' => ['Masseter' => 1], 'E4' => ['Temporalis' => 3]]))
        ->toBe([Tmd::MYALGIA]);
});

it('diagnoses Arthralgia from E4 TMJ pain', function () {
    $sq = ['SQ1' => 'Ya', 'SQ4' => 'Ya'];
    expect(diag($sq, ['E1' => ['TMJ' => 1], 'E4' => ['TMJ' => 2]]))->toBe([Tmd::ARTHRALGIA]);
});

it('diagnoses Myalgia and Arthralgia together', function () {
    $sq = ['SQ1' => 'Ya', 'SQ4' => 'Ya'];
    expect(diag($sq, ['E1' => ['TMJ' => 1], 'E4' => ['Masseter' => 1, 'TMJ' => 1]]))
        ->toBe([Tmd::MYALGIA, Tmd::ARTHRALGIA]);
});

it('requires SQ5 and E4 Temporalis for HA-TMD, and it comes together with Myalgia', function () {
    $e = ['E1' => ['Temporalis' => 1], 'E4' => ['Temporalis' => 1]];

    // Temporalis E4 = otot -> Myalgia ikut; HA-TMD hanya bila SQ5 = Ya.
    expect(diag(['SQ5' => 'Ya', 'SQ7' => 'Ya'], $e))->toBe([Tmd::MYALGIA, Tmd::HA_TMD]);
    expect(diag(['SQ1' => 'Ya', 'SQ4' => 'Ya'], $e))->toBe([Tmd::MYALGIA]);

    // SQ5 = Ya tetapi E4 Temporalis kosong -> tanpa HA-TMD.
    expect(diag(['SQ5' => 'Ya', 'SQ7' => 'Ya'], ['E1' => ['Temporalis' => 1], 'E4' => ['Masseter' => 1]]))
        ->toBe([Tmd::MYALGIA]);
    expect(diag(['SQ5' => 'Ya', 'SQ7' => 'Ya'], ['E1' => ['Temporalis' => 1], 'E4' => ['TMJ' => 1]]))
        ->toBe([Tmd::ARTHRALGIA]);
});

it('does not let SQ2, SQ3 and SQ6 influence the result', function () {
    $sq = ['SQ1' => 'Ya', 'SQ4' => 'Ya'];
    $eq = ['E1' => ['Masseter' => 1], 'E4' => ['Masseter' => 1]];
    $baseline = diag($sq, $eq);

    foreach (['< 1 minggu', '>= 1 minggu'] as $sq2) {
        foreach (['Persisten (terus-menerus)', 'Kambuhan (hilang timbul)', 'Tidak'] as $sq3) {
            foreach (['< 1x/minggu', '>= 1x/minggu', 'Tidak'] as $sq6) {
                expect(diag($sq + ['SQ2' => $sq2, 'SQ3' => $sq3, 'SQ6' => $sq6], $eq))->toBe($baseline);
            }
        }
    }

    // Tanpa SQ2/SQ3/SQ6 pun pola lama (mis. HA-TMD dari SQ5+SQ6+SQ7) tidak menghasilkan diagnosis.
    expect(diag(['SQ5' => 'Ya', 'SQ6' => '>= 1x/minggu', 'SQ7' => 'Ya']))->toBe(NONE_RESULT);
});

// ---------------------------------------------------------------- Jalur 2: START B (SQ9 + SQ10)

it('diagnoses Acute closed lock only with MUO < 35 mm and SQ12', function () {
    $sq = ['SQ9' => 'Ya', 'SQ10' => 'Ya', 'SQ12' => 'Ya'];

    expect(diag($sq, ['E2' => ['opening_mm' => 34]]))->toBe([Tmd::ACUTE_CLOSED_LOCK]);
    expect(diag($sq, ['E2' => ['opening_mm' => 34.9]]))->toBe([Tmd::ACUTE_CLOSED_LOCK]);
    expect(diag($sq, ['E2' => ['opening_mm' => '34']]))->toBe([Tmd::ACUTE_CLOSED_LOCK]);
    // Batas: tepat 35 mm BUKAN < 35.
    expect(diag($sq, ['E2' => ['opening_mm' => 35]]))->toBe([Tmd::OTHER_DISC_BASED]);
    expect(diag($sq, ['E2' => ['opening_mm' => 40]]))->toBe([Tmd::OTHER_DISC_BASED]);
});

it('falls back to Other Disc-based when opening is unknown or SQ12 is Tidak', function () {
    $sq = ['SQ9' => 'Ya', 'SQ10' => 'Ya', 'SQ12' => 'Ya'];

    expect(diag($sq, ['E2' => ['opening_mm' => '']]))->toBe([Tmd::OTHER_DISC_BASED]);
    expect(diag($sq, ['E2' => ['opening_mm' => null]]))->toBe([Tmd::OTHER_DISC_BASED]);
    expect(diag($sq, ['E2' => ['opening_mm' => 'abc']]))->toBe([Tmd::OTHER_DISC_BASED]);
    expect((new Tmd)->diagnose($sq, []))->toBe([Tmd::OTHER_DISC_BASED]);
    expect((new Tmd)->diagnose($sq, ['E2' => 'bukan array']))->toBe([Tmd::OTHER_DISC_BASED]);

    expect(diag(['SQ9' => 'Ya', 'SQ10' => 'Ya', 'SQ12' => 'Tidak'], ['E2' => ['opening_mm' => 20]]))
        ->toBe([Tmd::OTHER_DISC_BASED]);
});

it('needs both SQ9 and SQ10 for START B', function () {
    expect(diag(['SQ9' => 'Ya', 'SQ12' => 'Ya'], ['E2' => ['opening_mm' => 20]]))->toBe(NONE_RESULT);
    expect(diag(['SQ10' => 'Ya', 'SQ12' => 'Ya'], ['E2' => ['opening_mm' => 20]]))->toBe(NONE_RESULT);
});

it('only reaches the noise box when START B is "No"', function () {
    // SQ9 + SQ10 dengan krepitasi: START B = Ya -> TIDAK boleh DJD.
    expect(diag(['SQ9' => 'Ya', 'SQ10' => 'Ya'], ['E3' => 'Krepitasi']))
        ->toBe([Tmd::OTHER_DISC_BASED])
        ->not->toContain(Tmd::DEGENERATIVE_JOINT_DISEASE);
    expect(diag(['SQ9' => 'Ya', 'SQ10' => 'Ya', 'SQ12' => 'Ya'], ['E3' => 'Krepitasi', 'E2' => ['opening_mm' => 30]]))
        ->toBe([Tmd::ACUTE_CLOSED_LOCK]);
});

// ---------------------------------------------------------------- Jalur 2: kotak bunyi sendi

it('enters the noise box via SQ8 Sesekali, SQ8 Sering, E3 alone or SQ11', function (array $sq, array $eq, string $e3, string $expected) {
    expect(diag($sq, $eq + ['E3' => $e3]))->toBe([$expected]);
})->with(function () {
    $entries = [
        'SQ8 Sesekali' => [['SQ8' => 'Sesekali'], []],
        'SQ8 Sering' => [['SQ8' => 'Sering'], []],
        'E3 saja' => [[], []],
        'SQ11' => [['SQ11' => 'Ya'], []],
    ];

    foreach ($entries as $label => [$sq, $eq]) {
        yield "$label + Kliking" => [$sq, $eq, 'Kliking', Tmd::OTHER_DISC_BASED];
        yield "$label + Krepitasi" => [$sq, $eq, 'Krepitasi', Tmd::DEGENERATIVE_JOINT_DISEASE];
    }
});

it('gives nothing for noise by history alone without an E3 sound', function () {
    expect(diag(['SQ8' => 'Sesekali']))->toBe(NONE_RESULT);
    expect(diag(['SQ8' => 'Sering']))->toBe(NONE_RESULT);
    expect(diag(['SQ11' => 'Ya']))->toBe(NONE_RESULT);
    expect(diag(['SQ8' => 'Sering', 'SQ11' => 'Ya'], ['E3' => 'Tidak ada']))->toBe(NONE_RESULT);
});

// ---------------------------------------------------------------- Jalur 2: Subluxation (START C)

it('diagnoses Subluxation only with both SQ13 and SQ14', function () {
    expect(diag(['SQ13' => 'Ya', 'SQ14' => 'Ya']))->toBe([Tmd::SUBLUXATION]);
    expect(diag(['SQ13' => 'Ya']))->toBe(NONE_RESULT);
    expect(diag(['SQ14' => 'Ya']))->toBe(NONE_RESULT);
});

it('combines Subluxation with the START B result', function () {
    expect(diag(['SQ9' => 'Ya', 'SQ10' => 'Ya', 'SQ13' => 'Ya', 'SQ14' => 'Ya']))
        ->toBe([Tmd::OTHER_DISC_BASED, Tmd::SUBLUXATION]);
    expect(diag(['SQ13' => 'Ya', 'SQ14' => 'Ya'], ['E3' => 'Krepitasi']))
        ->toBe([Tmd::DEGENERATIVE_JOINT_DISEASE, Tmd::SUBLUXATION]);
});

// ---------------------------------------------------------------- Gabungan

it('returns pain and joint diagnoses together in the fixed order', function () {
    $sq = [
        'SQ1' => 'Ya', 'SQ5' => 'Ya', 'SQ4' => 'Ya',
        'SQ9' => 'Ya', 'SQ10' => 'Ya', 'SQ12' => 'Ya', 'SQ13' => 'Ya', 'SQ14' => 'Ya',
    ];
    $eq = [
        'E1' => ['TMJ' => 1],
        'E2' => ['opening_mm' => 30],
        'E4' => ['Temporalis' => 1, 'Masseter' => 1, 'TMJ' => 1],
    ];

    expect(diag($sq, $eq))->toBe([
        Tmd::MYALGIA, Tmd::ARTHRALGIA, Tmd::HA_TMD, Tmd::ACUTE_CLOSED_LOCK, Tmd::SUBLUXATION,
    ]);
});

it('returns the fallback message for empty input', function () {
    expect((new Tmd)->diagnose([], []))->toBe(['No specific TMD diagnosis found.']);
    expect(diag())->toBe(NONE_RESULT);
});

it('never produces the legacy Joint-related TMD name', function () {
    expect(diag(['SQ9' => 'Ya', 'SQ10' => 'Ya', 'SQ13' => 'Ya', 'SQ14' => 'Ya', 'SQ8' => 'Sering'], ['E3' => 'Krepitasi']))
        ->not->toContain(Tmd::JOINT_RELATED);
});
