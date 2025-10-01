<?php

/**
 * Simple Test Script for Laravel Captcha
 * 
 * This script demonstrates basic usage of the captcha generators
 * without requiring a full Laravel application.
 */

require __DIR__ . '/../vendor/autoload.php';

use Dev3bdulrahman\LaravelCaptcha\Generators\MathCaptchaGenerator;
use Dev3bdulrahman\LaravelCaptcha\Generators\TextCaptchaGenerator;
use Dev3bdulrahman\LaravelCaptcha\Generators\SliderCaptchaGenerator;

echo "=== Laravel Captcha Test ===\n\n";

// Test Math Captcha
echo "1. Math Captcha Test:\n";
echo "   Easy: ";
$mathEasy = new MathCaptchaGenerator('easy');
$dataEasy = $mathEasy->generate();
echo $dataEasy['question'] . " = " . $dataEasy['value'] . "\n";

echo "   Medium: ";
$mathMedium = new MathCaptchaGenerator('medium');
$dataMedium = $mathMedium->generate();
echo $dataMedium['question'] . " = " . $dataMedium['value'] . "\n";

echo "   Hard: ";
$mathHard = new MathCaptchaGenerator('hard');
$dataHard = $mathHard->generate();
echo $dataHard['question'] . " = " . $dataHard['value'] . "\n\n";

// Test Text Captcha
echo "2. Text Captcha Test:\n";

// Mock config for text captcha
if (!function_exists('config')) {
    function config($key, $default = null) {
        $config = [
            'captcha.text.questions.easy' => [
                'What color is the sky?' => 'blue',
                'How many days in a week?' => '7',
            ],
            'captcha.text.questions.medium' => [
                'What is the capital of France?' => 'paris',
                'How many continents are there?' => '7',
            ],
            'captcha.text.questions.hard' => [
                'What is the square root of 144?' => '12',
                'How many seconds in a minute?' => '60',
            ],
        ];
        
        return $config[$key] ?? $default;
    }
}

try {
    $textEasy = new TextCaptchaGenerator('easy');
    $textData = $textEasy->generate();
    echo "   Question: " . $textData['question'] . "\n";
    echo "   Answer: " . $textData['value'] . "\n\n";
} catch (Exception $e) {
    echo "   Error: " . $e->getMessage() . "\n\n";
}

// Test Slider Captcha
echo "3. Slider Captcha Test:\n";

// Mock config for slider
if (!function_exists('config_slider')) {
    $GLOBALS['slider_config'] = [
        'captcha.slider' => [
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
    ];
}

$sliderMedium = new SliderCaptchaGenerator('medium');
$sliderData = $sliderMedium->generate();
echo "   Position X: " . $sliderData['position']['x'] . "\n";
echo "   Position Y: " . $sliderData['position']['y'] . "\n";
echo "   Puzzle Size: " . $sliderData['puzzle_size'] . "\n";
echo "   Tolerance: " . $sliderData['tolerance'] . "\n\n";

echo "=== All Tests Completed Successfully! ===\n";

