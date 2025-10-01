<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Default Captcha Type
    |--------------------------------------------------------------------------
    |
    | This option controls the default captcha type that will be used.
    | Available types: 'image', 'math', 'text', 'slider', 'puzzle'
    |
    */
    'default' => env('CAPTCHA_TYPE', 'image'),

    /*
    |--------------------------------------------------------------------------
    | Default Difficulty Level
    |--------------------------------------------------------------------------
    |
    | This option controls the default difficulty level.
    | Available levels: 'easy', 'medium', 'hard'
    |
    */
    'difficulty' => env('CAPTCHA_DIFFICULTY', 'medium'),

    /*
    |--------------------------------------------------------------------------
    | Session Key
    |--------------------------------------------------------------------------
    |
    | The session key used to store the captcha value.
    |
    */
    'session_key' => 'laravel_captcha',

    /*
    |--------------------------------------------------------------------------
    | Expiration Time
    |--------------------------------------------------------------------------
    |
    | The time in minutes before the captcha expires.
    |
    */
    'expire' => 5,

    /*
    |--------------------------------------------------------------------------
    | Image Captcha Settings
    |--------------------------------------------------------------------------
    */
    'image' => [
        'width' => 200,
        'height' => 60,
        'length' => [
            'easy' => 4,
            'medium' => 5,
            'hard' => 6,
        ],
        'characters' => [
            'easy' => '23456789ABCDEFGHJKLMNPQRSTUVWXYZ',
            'medium' => '23456789ABCDEFGHJKLMNPQRSTUVWXYZabcdefghjkmnpqrstuvwxyz',
            'hard' => '23456789ABCDEFGHJKLMNPQRSTUVWXYZabcdefghjkmnpqrstuvwxyz!@#$%^&*',
        ],
        'fonts' => [
            'OpenSans-Bold.ttf',
            'Roboto-Bold.ttf',
            'Montserrat-Bold.ttf',
        ],
        'colors' => [
            'background' => [255, 255, 255],
            'text' => [0, 0, 0],
            'noise' => [200, 200, 200],
        ],
        'noise' => [
            'easy' => 5,
            'medium' => 10,
            'hard' => 20,
        ],
        'lines' => [
            'easy' => 2,
            'medium' => 4,
            'hard' => 6,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Math Captcha Settings
    |--------------------------------------------------------------------------
    */
    'math' => [
        'operators' => [
            'easy' => ['+', '-'],
            'medium' => ['+', '-', '*'],
            'hard' => ['+', '-', '*', '/'],
        ],
        'range' => [
            'easy' => [1, 10],
            'medium' => [1, 50],
            'hard' => [1, 100],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Text Captcha Settings
    |--------------------------------------------------------------------------
    */
    'text' => [
        'questions' => [
            'easy' => [
                'What color is the sky?' => 'blue',
                'How many days in a week?' => '7',
                'What is 2 + 2?' => '4',
                'What comes after Monday?' => 'tuesday',
                'How many legs does a cat have?' => '4',
            ],
            'medium' => [
                'What is the capital of France?' => 'paris',
                'How many continents are there?' => '7',
                'What is the opposite of hot?' => 'cold',
                'What is 5 * 5?' => '25',
                'What planet do we live on?' => 'earth',
            ],
            'hard' => [
                'What is the square root of 144?' => '12',
                'How many seconds in a minute?' => '60',
                'What is the chemical symbol for water?' => 'h2o',
                'How many sides does a hexagon have?' => '6',
                'What is 15 * 8?' => '120',
            ],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Slider Captcha Settings
    |--------------------------------------------------------------------------
    */
    'slider' => [
        'width' => 300,
        'height' => 150,
        'puzzle_size' => [
            'easy' => 40,
            'medium' => 50,
            'hard' => 60,
        ],
        'tolerance' => [
            'easy' => 10,
            'medium' => 5,
            'hard' => 3,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Styles
    |--------------------------------------------------------------------------
    |
    | Different visual styles for captcha display
    | Available: 'default', 'modern', 'minimal', 'colorful'
    |
    */
    'style' => env('CAPTCHA_STYLE', 'default'),

    /*
    |--------------------------------------------------------------------------
    | Case Sensitive
    |--------------------------------------------------------------------------
    |
    | Whether the captcha validation should be case sensitive.
    |
    */
    'case_sensitive' => false,

    /*
    |--------------------------------------------------------------------------
    | Routes
    |--------------------------------------------------------------------------
    |
    | Enable or disable automatic route registration.
    |
    */
    'routes' => [
        'enabled' => true,
        'prefix' => 'captcha',
        'middleware' => ['web'],
    ],
];

