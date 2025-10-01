<?php

namespace Dev3bdulrahman\LaravelCaptcha\Generators;

abstract class CaptchaGenerator
{
    /**
     * Difficulty level.
     *
     * @var string
     */
    protected $difficulty;

    /**
     * Create a new generator instance.
     *
     * @param string $difficulty
     */
    public function __construct(string $difficulty = 'medium')
    {
        $this->difficulty = in_array($difficulty, ['easy', 'medium', 'hard']) 
            ? $difficulty 
            : 'medium';
    }

    /**
     * Generate captcha data.
     *
     * @return array
     */
    abstract public function generate(): array;

    /**
     * Get difficulty level.
     *
     * @return string
     */
    public function getDifficulty(): string
    {
        return $this->difficulty;
    }

    /**
     * Generate random string.
     *
     * @param int $length
     * @param string $characters
     * @return string
     */
    protected function randomString(int $length, string $characters): string
    {
        $result = '';
        $max = strlen($characters) - 1;
        
        for ($i = 0; $i < $length; $i++) {
            $result .= $characters[random_int(0, $max)];
        }
        
        return $result;
    }
}

