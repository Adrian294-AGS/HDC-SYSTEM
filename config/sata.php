<?php

/*
|--------------------------------------------------------------------------
| SATA (Scholastic Abilities Test for Adults) settings
|--------------------------------------------------------------------------
|
| HDC can adjust the descriptor ranges and score limits here. The encoding
| form suggests a descriptor from these ranges as soon as a score is typed;
| staff can still choose a different one.
|
*/

return [

    // Subtest code => name, in the order they appear on the paper form.
    'subtests' => [
        'vr' => ['code' => 'VR', 'name' => 'Verbal Reasoning'],
        'nvr' => ['code' => 'NVR', 'name' => 'Non-verbal Reasoning'],
        'qr' => ['code' => 'QR', 'name' => 'Quantitative Reasoning'],
        'rv' => ['code' => 'RV', 'name' => 'Reading Vocabulary'],
        'rc' => ['code' => 'RC', 'name' => 'Reading Comprehension'],
        'mc' => ['code' => 'MC', 'name' => 'Math Calculation'],
        'ma' => ['code' => 'MA', 'name' => 'Math Application'],
        'wm' => ['code' => 'WM', 'name' => 'Writing Mechanics'],
        'wc' => ['code' => 'WC', 'name' => 'Writing Composition'],
    ],

    // Allowed descriptor choices, highest to lowest.
    'descriptors' => [
        'Very Superior',
        'Superior',
        'Above Average',
        'Average',
        'Below Average',
        'Poor',
        'Very Poor',
    ],

    // Valid score ranges (validation).
    'limits' => [
        'subtest' => ['min' => 1, 'max' => 20],
        'quotient' => ['min' => 40, 'max' => 160],
    ],

    // Descriptor suggestion ranges. "min"/"max" are inclusive; null means open-ended.
    'ranges' => [
        'subtest' => [
            ['min' => 17, 'max' => 20, 'descriptor' => 'Very Superior'],
            ['min' => 15, 'max' => 16, 'descriptor' => 'Superior'],
            ['min' => 13, 'max' => 14, 'descriptor' => 'Above Average'],
            ['min' => 8, 'max' => 12, 'descriptor' => 'Average'],
            ['min' => 6, 'max' => 7, 'descriptor' => 'Below Average'],
            ['min' => 4, 'max' => 5, 'descriptor' => 'Poor'],
            ['min' => 1, 'max' => 3, 'descriptor' => 'Very Poor'],
        ],
        'quotient' => [
            ['min' => 131, 'max' => null, 'descriptor' => 'Very Superior'],
            ['min' => 121, 'max' => 130, 'descriptor' => 'Superior'],
            ['min' => 111, 'max' => 120, 'descriptor' => 'Above Average'],
            ['min' => 90, 'max' => 110, 'descriptor' => 'Average'],
            ['min' => 80, 'max' => 89, 'descriptor' => 'Below Average'],
            ['min' => 70, 'max' => 79, 'descriptor' => 'Poor'],
            ['min' => null, 'max' => 69, 'descriptor' => 'Very Poor'],
        ],
    ],

];
