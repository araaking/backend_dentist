<?php

use App\Models\Diagnosis;
use App\Services\TmdDiagnosisService as Tmd;
use App\Support\TmdQuestionnaire;

// Penjaga: service membandingkan literal opsi; bila opsi di kuesioner diganti, tes ini gagal.

it('keeps the service literals in sync with the questionnaire options', function () {
    foreach (['SQ1', 'SQ4', 'SQ5', 'SQ7', 'SQ9', 'SQ10', 'SQ11', 'SQ12', 'SQ13', 'SQ14'] as $code) {
        expect(TmdQuestionnaire::sq()[$code]['options'])->toContain('Ya');
    }

    expect(TmdQuestionnaire::sq()['SQ8']['options'])->toContain('Sesekali', 'Sering');
    expect(TmdQuestionnaire::e3Values())->toContain('Kliking', 'Krepitasi');
    expect(TmdQuestionnaire::AREAS)->toContain('Temporalis', 'Masseter', 'TMJ');
    expect(TmdQuestionnaire::SCORE_GROUPS)->toContain('E1', 'E4');
});

it('has a non-empty description for every diagnosis name', function () {
    $names = [
        Tmd::MYALGIA, Tmd::ARTHRALGIA, Tmd::HA_TMD, Tmd::DEGENERATIVE_JOINT_DISEASE, Tmd::ACUTE_CLOSED_LOCK,
        Tmd::OTHER_DISC_BASED, Tmd::SUBLUXATION, Tmd::JOINT_RELATED, Tmd::NONE,
    ];

    foreach ($names as $name) {
        expect(Diagnosis::DESCRIPTIONS[$name] ?? '')->not->toBe('');
    }
});
