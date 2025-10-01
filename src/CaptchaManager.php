<?php

namespace Dev3bdulrahman\LaravelCaptcha;

use Illuminate\Support\Facades\Session;
use Dev3bdulrahman\LaravelCaptcha\Generators\ImageCaptchaGenerator;
use Dev3bdulrahman\LaravelCaptcha\Generators\MathCaptchaGenerator;
use Dev3bdulrahman\LaravelCaptcha\Generators\TextCaptchaGenerator;
use Dev3bdulrahman\LaravelCaptcha\Generators\SliderCaptchaGenerator;

class CaptchaManager
{
    /**
     * Application instance.
     *
     * @var \Illuminate\Foundation\Application
     */
    protected $app;

    /**
     * Captcha generators.
     *
     * @var array
     */
    protected $generators = [];

    /**
     * Create a new captcha manager instance.
     *
     * @param \Illuminate\Foundation\Application $app
     */
    public function __construct($app)
    {
        $this->app = $app;
        $this->registerGenerators();
    }

    /**
     * Register captcha generators.
     */
    protected function registerGenerators(): void
    {
        $this->generators = [
            'image' => ImageCaptchaGenerator::class,
            'math' => MathCaptchaGenerator::class,
            'text' => TextCaptchaGenerator::class,
            'slider' => SliderCaptchaGenerator::class,
        ];
    }

    /**
     * Generate a new captcha.
     *
     * @param string|null $type
     * @param string|null $difficulty
     * @return array
     */
    public function generate(?string $type = null, ?string $difficulty = null): array
    {
        $type = $type ?? config('captcha.default', 'image');
        $difficulty = $difficulty ?? config('captcha.difficulty', 'medium');

        if (!isset($this->generators[$type])) {
            throw new \InvalidArgumentException("Captcha type [{$type}] is not supported.");
        }

        $generator = new $this->generators[$type]($difficulty);
        $data = $generator->generate();

        // Store in session
        $sessionKey = config('captcha.session_key', 'laravel_captcha');
        Session::put("{$sessionKey}.{$type}", [
            'value' => $data['value'],
            'expires_at' => now()->addMinutes(config('captcha.expire', 5)),
        ]);

        return $data;
    }

    /**
     * Verify captcha input.
     *
     * @param string $input
     * @param string|null $type
     * @return bool
     */
    public function verify(string $input, ?string $type = null): bool
    {
        $type = $type ?? config('captcha.default', 'image');
        $sessionKey = config('captcha.session_key', 'laravel_captcha');
        
        $captchaData = Session::get("{$sessionKey}.{$type}");

        if (!$captchaData) {
            return false;
        }

        // Check expiration
        if (now()->greaterThan($captchaData['expires_at'])) {
            Session::forget("{$sessionKey}.{$type}");
            return false;
        }

        $caseSensitive = config('captcha.case_sensitive', false);
        $expectedValue = $captchaData['value'];
        
        if (!$caseSensitive) {
            $input = strtolower($input);
            $expectedValue = strtolower($expectedValue);
        }

        $isValid = $input === $expectedValue;

        // Clear session after verification
        if ($isValid) {
            Session::forget("{$sessionKey}.{$type}");
        }

        return $isValid;
    }

    /**
     * Get captcha image.
     *
     * @param string|null $type
     * @param string|null $difficulty
     * @return \Illuminate\Http\Response
     */
    public function image(?string $type = null, ?string $difficulty = null)
    {
        $type = $type ?? 'image';
        $difficulty = $difficulty ?? config('captcha.difficulty', 'medium');

        if (!isset($this->generators[$type])) {
            throw new \InvalidArgumentException("Captcha type [{$type}] is not supported.");
        }

        $generator = new $this->generators[$type]($difficulty);
        
        if (!method_exists($generator, 'image')) {
            throw new \BadMethodCallException("Generator [{$type}] does not support image generation.");
        }

        return $generator->image();
    }

    /**
     * Get captcha data.
     *
     * @param string|null $type
     * @return array|null
     */
    public function getData(?string $type = null): ?array
    {
        $type = $type ?? config('captcha.default', 'image');
        $sessionKey = config('captcha.session_key', 'laravel_captcha');
        
        return Session::get("{$sessionKey}.{$type}");
    }

    /**
     * Refresh captcha.
     *
     * @param string|null $type
     * @return void
     */
    public function refresh(?string $type = null): void
    {
        $type = $type ?? config('captcha.default', 'image');
        $sessionKey = config('captcha.session_key', 'laravel_captcha');
        
        Session::forget("{$sessionKey}.{$type}");
    }

    /**
     * Clear all captcha sessions.
     *
     * @return void
     */
    public function clear(): void
    {
        $sessionKey = config('captcha.session_key', 'laravel_captcha');
        
        foreach (array_keys($this->generators) as $type) {
            Session::forget("{$sessionKey}.{$type}");
        }
    }
}

