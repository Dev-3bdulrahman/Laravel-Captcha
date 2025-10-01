<?php

namespace Dev3bdulrahman\LaravelCaptcha\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static string generate(string $type = null, string $difficulty = null)
 * @method static bool verify(string $input, string $type = null)
 * @method static string image(string $type = null, string $difficulty = null)
 * @method static array getData(string $type = null)
 * @method static void refresh()
 * 
 * @see \Dev3bdulrahman\LaravelCaptcha\CaptchaManager
 */
class Captcha extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'captcha';
    }
}

