<?php

if (!function_exists('captcha')) {
    /**
     * Generate captcha helper.
     *
     * @param string|null $type
     * @param string|null $difficulty
     * @return array
     */
    function captcha(?string $type = null, ?string $difficulty = null): array
    {
        return app('captcha')->generate($type, $difficulty);
    }
}

if (!function_exists('captcha_verify')) {
    /**
     * Verify captcha helper.
     *
     * @param string $input
     * @param string|null $type
     * @return bool
     */
    function captcha_verify(string $input, ?string $type = null): bool
    {
        return app('captcha')->verify($input, $type);
    }
}

if (!function_exists('captcha_img')) {
    /**
     * Get captcha image URL.
     *
     * @param string|null $type
     * @param string|null $difficulty
     * @return string
     */
    function captcha_img(?string $type = null, ?string $difficulty = null): string
    {
        $type = $type ?? 'image';
        $difficulty = $difficulty ?? config('captcha.difficulty', 'medium');
        
        return route('captcha.image', ['type' => $type, 'difficulty' => $difficulty]);
    }
}

if (!function_exists('captcha_refresh')) {
    /**
     * Refresh captcha.
     *
     * @param string|null $type
     * @return void
     */
    function captcha_refresh(?string $type = null): void
    {
        app('captcha')->refresh($type);
    }
}

