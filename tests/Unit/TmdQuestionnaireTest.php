<?php

use App\Support\TmdQuestionnaire;

it('sanitize keeps only known codes and normalizes types', function () {
    $clean = TmdQuestionnaire::sanitize(
        ['SQ1' => 'Ya', 'SQ99' => 'Ya', 'SQ2' => 'bukan opsi', str_repeat('X', 300) => 'Ya'],
        [
            'E1' => ['Temporalis' => '2', 'Dagu' => 3, 'TMJ' => 9],
            'E2' => ['opening_mm' => '42'],
            'E3' => 'Krepitasi',
            'E9' => 'x',
        ],
    );

    expect($clean['sq'])->toBe(['SQ1' => 'Ya']);
    expect($clean['eq'])->toBe([
        'E1' => ['Temporalis' => 2],
        'E2' => ['opening_mm' => 42],
        'E3' => 'Krepitasi',
    ]);
});

it('has 14 SQ and 4 EQ definitions', function () {
    expect(TmdQuestionnaire::sqCodes())->toHaveCount(14);
    expect(array_keys(TmdQuestionnaire::eqTitles()))->toBe(['E1', 'E2', 'E3', 'E4']);
});
