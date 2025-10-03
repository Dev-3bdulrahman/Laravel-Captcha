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
        $expiresAt = now()->addMinutes(config('captcha.expire', 5));

        Session::put("{$sessionKey}.{$type}", [
            'value' => $data['value'],
            'expires_at' => $expiresAt,
        ]);

        // Log: Captcha generated
        \Log::info('🎨 CAPTCHA GENERATED', [
            'type' => $type,
            'difficulty' => $difficulty,
            'value' => $data['value'],
            'session_key' => "{$sessionKey}.{$type}",
            'expires_at' => $expiresAt->toDateTimeString(),
            'session_id' => Session::getId()
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

        // Log: Start verification
        \Log::info('🔍 CAPTCHA VERIFICATION START', [
            'input' => $input,
            'type' => $type,
            'session_key' => $sessionKey,
            'full_session_key' => "{$sessionKey}.{$type}"
        ]);

        $captchaData = Session::get("{$sessionKey}.{$type}");

        if (!$captchaData) {
            \Log::warning('❌ CAPTCHA VERIFICATION FAILED: No captcha data in session', [
                'session_key' => "{$sessionKey}.{$type}",
                'all_session_keys' => array_keys(Session::all())
            ]);
            return false;
        }

        // Log: Captcha data found
        \Log::info('✅ CAPTCHA DATA FOUND', [
            'expected_value' => $captchaData['value'] ?? 'N/A',
            'expires_at' => $captchaData['expires_at'] ?? 'N/A',
            'current_time' => now()->toDateTimeString()
        ]);

        // Check expiration
        if (now()->greaterThan($captchaData['expires_at'])) {
            \Log::warning('❌ CAPTCHA VERIFICATION FAILED: Expired', [
                'expires_at' => $captchaData['expires_at'],
                'current_time' => now()->toDateTimeString()
            ]);
            Session::forget("{$sessionKey}.{$type}");
            return false;
        }

        $caseSensitive = config('captcha.case_sensitive', false);
        $expectedValue = $captchaData['value'];

        // Log: Before case conversion
        \Log::info('🔄 CAPTCHA COMPARISON', [
            'input_original' => $input,
            'expected_original' => $expectedValue,
            'case_sensitive' => $caseSensitive
        ]);

        if (!$caseSensitive) {
            $input = strtolower($input);
            $expectedValue = strtolower($expectedValue);
        }

        // Log: After case conversion
        \Log::info('🔄 CAPTCHA COMPARISON (after case conversion)', [
            'input_processed' => $input,
            'expected_processed' => $expectedValue,
            'input_length' => strlen($input),
            'expected_length' => strlen($expectedValue)
        ]);

        $isValid = $input === $expectedValue;

        // Log: Final result
        if ($isValid) {
            \Log::info('✅ CAPTCHA VERIFICATION SUCCESS', [
                'input' => $input,
                'expected' => $expectedValue
            ]);
            Session::forget("{$sessionKey}.{$type}");
        } else {
            \Log::error('❌ CAPTCHA VERIFICATION FAILED: Mismatch', [
                'input' => $input,
                'expected' => $expectedValue,
                'input_hex' => bin2hex($input),
                'expected_hex' => bin2hex($expectedValue)
            ]);
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
     * Get captcha SVG image.
     *
     * @param string|null $type
     * @param string|null $difficulty
     * @return \Illuminate\Http\Response
     */
    public function svg(?string $type = null, ?string $difficulty = null)
    {
        $type = $type ?? 'image';
        $difficulty = $difficulty ?? config('captcha.difficulty', 'medium');

        if (!isset($this->generators[$type])) {
            throw new \InvalidArgumentException("Captcha type [{$type}] is not supported.");
        }

        $generator = new $this->generators[$type]($difficulty);

        if (!method_exists($generator, 'svg')) {
            throw new \BadMethodCallException("Generator [{$type}] does not support SVG generation.");
        }

        // Generate captcha data and store in session
        $data = $generator->generate();

        // Store in session
        $sessionKey = config('captcha.session_key', 'laravel_captcha');
        $expiresAt = now()->addMinutes(config('captcha.expire', 5));

        Session::put("{$sessionKey}.{$type}", [
            'value' => $data['value'],
            'expires_at' => $expiresAt,
        ]);

        // Log: Captcha generated via SVG route
        \Log::info('🎨 CAPTCHA GENERATED (SVG Route)', [
            'type' => $type,
            'difficulty' => $difficulty,
            'value' => $data['value'],
            'session_key' => "{$sessionKey}.{$type}",
            'expires_at' => $expiresAt->toDateTimeString(),
            'session_id' => Session::getId()
        ]);

        return $generator->svg();
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

