<?php

namespace Dev3bdulrahman\LaravelCaptcha\Generators;

class SliderCaptchaGenerator extends CaptchaGenerator
{
    /**
     * Generate slider captcha.
     *
     * @return array
     */
    public function generate(): array
    {
        $config = config('captcha.slider');
        $width = $config['width'];
        $height = $config['height'];
        $puzzleSize = $config['puzzle_size'][$this->difficulty];
        
        // Generate random position for puzzle piece
        $x = random_int($puzzleSize + 10, $width - $puzzleSize - 10);
        $y = random_int(10, $height - $puzzleSize - 10);

        return [
            'type' => 'slider',
            'width' => $width,
            'height' => $height,
            'puzzle_size' => $puzzleSize,
            'position' => ['x' => $x, 'y' => $y],
            'value' => (string) $x,
            'tolerance' => $config['tolerance'][$this->difficulty],
            'difficulty' => $this->difficulty,
            'background_image' => $this->generateBackgroundImage($width, $height),
        ];
    }

    /**
     * Generate a simple background pattern.
     *
     * @param int $width
     * @param int $height
     * @return string
     */
    protected function generateBackgroundImage(int $width, int $height): string
    {
        // Generate a random gradient background
        $colors = [
            ['#667eea', '#764ba2'],
            ['#f093fb', '#f5576c'],
            ['#4facfe', '#00f2fe'],
            ['#43e97b', '#38f9d7'],
            ['#fa709a', '#fee140'],
        ];

        $gradient = $colors[array_rand($colors)];

        return "linear-gradient(135deg, {$gradient[0]} 0%, {$gradient[1]} 100%)";
    }
}

