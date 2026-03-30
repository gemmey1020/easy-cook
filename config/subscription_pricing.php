<?php

return [
    'three_week_ui_enabled' => filter_var(
        env('SUBSCRIPTION_THREE_WEEK_UI_ENABLED', in_array(env('APP_ENV'), ['local', 'testing'], true)),
        FILTER_VALIDATE_BOOL
    ),

    'catalog' => [
        1400 => [
            5 => [
                3 => [
                    'with_vat' => 16986,
                    'without_vat' => 14900,
                ],
                4 => [
                    'with_vat' => 22344,
                    'without_vat' => 19600,
                ],
            ],
            6 => [
                3 => [
                    'with_vat' => 20349,
                    'without_vat' => 17850,
                ],
                4 => [
                    'with_vat' => 26790,
                    'without_vat' => 23500,
                ],
            ],
        ],
        1700 => [
            5 => [
                3 => [
                    'with_vat' => 18810,
                    'without_vat' => 16500,
                ],
                4 => [
                    'with_vat' => 24738,
                    'without_vat' => 21700,
                ],
            ],
            6 => [
                3 => [
                    'with_vat' => 22572,
                    'without_vat' => 19800,
                ],
                4 => [
                    'with_vat' => 29640,
                    'without_vat' => 26000,
                ],
            ],
        ],
        2000 => [
            5 => [
                3 => [
                    'with_vat' => 19836,
                    'without_vat' => 17400,
                ],
                4 => [
                    'with_vat' => 26106,
                    'without_vat' => 22900,
                ],
            ],
            6 => [
                3 => [
                    'with_vat' => 23826,
                    'without_vat' => 20900,
                ],
                4 => [
                    'with_vat' => 31350,
                    'without_vat' => 27500,
                ],
            ],
        ],
    ],
];
