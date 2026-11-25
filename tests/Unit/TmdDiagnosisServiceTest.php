<?php

use App\Services\TmdDiagnosisService;

function diag(array $sq = [], array $eq = []): array
{
    $defaultSq = [
        'SQ1' => 'Tidak', 'SQ2' => '< 1 minggu', 'SQ3' => 'Tidak', 'SQ4' => 'Tidak', 'SQ5' => 'Tidak',
        'SQ6' => 'Tidak', 'SQ7' => 'Tidak', 'SQ8' => 'Tidak ada', 'SQ9' => 'Tidak', 'SQ10' => 'Tidak',
        'SQ11' => 'Tidak', 'SQ12' => 'Tidak', 'SQ13' => 'Tidak', 'SQ14' => 'Tidak',
    ];
    $zeros = ['Temporalis' => 0, 'Masseter' => 0, 'TMJ' => 0];
    $defaultEq = ['E1' => $zeros, 'E2' => ['opening_mm' => 40], 'E3' => 'Tidak ada', 'E4' => $zeros];

    return (new TmdDiagnosisService())->diagnose(
        array_replace($defaultSq, $sq),
        array_replace_recursive($defaultEq, $eq),
    );
}

$myalgiaSq = ['SQ1' => 'Ya', 'SQ2' => '>= 1 minggu', 'SQ3' => 'Ya', 'SQ4' => 'Ya'];

it('diagnoses Myalgia from temporalis/masseter pain on E1', function () use ($myalgiaSq) {
    expect(diag($myalgiaSq, ['E1' => ['Temporalis' => 1]]))->toBe(['Myalgia']);
    expect(diag($myalgiaSq, ['E1' => ['Masseter' => 2]]))->toBe(['Myalgia']);
});

it('diagnoses Myalgia from E4 pain', function () use ($myalgiaSq) {
    expect(diag($myalgiaSq, ['E4' => ['Temporalis' => 3]]))->toBe(['Myalgia']);
    expect(diag($myalgiaSq, ['E4' => ['Masseter' => 1]]))->toBe(['Myalgia']);
});

it('does not diagnose Myalgia without muscle pain or with SQ2 < 1 week', function () use ($myalgiaSq) {
    expect(diag($myalgiaSq))->toBe([TmdDiagnosisService::NONE]);
    expect(diag(array_replace($myalgiaSq, ['SQ2' => '< 1 minggu']), ['E1' => ['Temporalis' => 2]]))
        ->not->toContain('Myalgia');
});

it('diagnoses Arthralgia from TMJ pain', function () {
    expect(diag(['SQ1' => 'Ya', 'SQ4' => 'Ya'], ['E1' => ['TMJ' => 1]]))->toBe(['Arthralgia']);
    expect(diag(['SQ1' => 'Ya', 'SQ4' => 'Ya'], ['E4' => ['TMJ' => 2]]))->toBe(['Arthralgia']);
});

it('diagnoses HA-TMD', function () {
    expect(diag(['SQ5' => 'Ya', 'SQ6' => '>= 1x/minggu', 'SQ7' => 'Ya']))
        ->toBe(['Headache attributed to TMD (HA-TMD)']);
    expect(diag(['SQ5' => 'Ya', 'SQ6' => '< 1x/minggu', 'SQ7' => 'Ya']))
        ->toBe([TmdDiagnosisService::NONE]);
});

it('diagnoses Joint-related TMD with at least two joint symptoms', function () {
    expect(diag(['SQ9' => 'Ya', 'SQ10' => 'Ya']))->toBe(['Joint-related TMD']);
    expect(diag(['SQ8' => 'Sering', 'SQ14' => 'Ya']))->toBe(['Joint-related TMD']);
    expect(diag(['SQ9' => 'Ya'], ['E3' => 'Krepitasi']))->toBe(['Joint-related TMD']);
    expect(diag(['SQ9' => 'Ya'], ['E2' => ['opening_mm' => 30]]))->toBe(['Joint-related TMD']);
});

it('falls back to the no-diagnosis message', function () {
    expect(diag())->toBe(['No specific TMD diagnosis found.']);
    // satu gejala sendi saja tidak cukup
    expect(diag(['SQ9' => 'Ya']))->toBe([TmdDiagnosisService::NONE]);
    expect(diag(['SQ8' => 'Sesekali']))->toBe([TmdDiagnosisService::NONE]);
});

it('keeps the rules mutually exclusive in clinical order', function () use ($myalgiaSq) {
    // Myalgia + Arthralgia + HA-TMD + Joint terpenuhi sekaligus -> hanya Myalgia.
    $sq = array_merge($myalgiaSq, [
        'SQ5' => 'Ya', 'SQ6' => '>= 1x/minggu', 'SQ7' => 'Ya', 'SQ9' => 'Ya', 'SQ10' => 'Ya',
    ]);
    $eq = ['E1' => ['Temporalis' => 1, 'TMJ' => 3]];
    expect(diag($sq, $eq))->toBe(['Myalgia']);

    // Arthralgia mengalahkan HA-TMD dan Joint.
    $sq = ['SQ1' => 'Ya', 'SQ4' => 'Ya', 'SQ5' => 'Ya', 'SQ6' => '>= 1x/minggu', 'SQ7' => 'Ya', 'SQ9' => 'Ya', 'SQ10' => 'Ya'];
    expect(diag($sq, ['E4' => ['TMJ' => 1]]))->toBe(['Arthralgia']);

    // HA-TMD mengalahkan Joint.
    $sq = ['SQ5' => 'Ya', 'SQ6' => '>= 1x/minggu', 'SQ7' => 'Ya', 'SQ9' => 'Ya', 'SQ10' => 'Ya'];
    expect(diag($sq))->toBe(['Headache attributed to TMD (HA-TMD)']);
});

it('does not count empty, null or non-numeric opening_mm as <= 35', function () {
    // SQ9 = 1 gejala; opening_mm kosong tidak boleh menjadi gejala ke-2.
    expect(diag(['SQ9' => 'Ya'], ['E2' => ['opening_mm' => '']]))->toBe([TmdDiagnosisService::NONE]);
    expect(diag(['SQ9' => 'Ya'], ['E2' => ['opening_mm' => null]]))->toBe([TmdDiagnosisService::NONE]);
    expect(diag(['SQ9' => 'Ya'], ['E2' => ['opening_mm' => 'abc']]))->toBe([TmdDiagnosisService::NONE]);

    $service = new TmdDiagnosisService();
    expect($service->diagnose(['SQ9' => 'Ya'], []))->toBe([TmdDiagnosisService::NONE]);

    // Nilai numerik <= 35 (termasuk string numerik dan batas 35) tetap dihitung.
    expect(diag(['SQ9' => 'Ya'], ['E2' => ['opening_mm' => '35']]))->toBe(['Joint-related TMD']);
    expect(diag(['SQ9' => 'Ya'], ['E2' => ['opening_mm' => 36]]))->toBe([TmdDiagnosisService::NONE]);
});
