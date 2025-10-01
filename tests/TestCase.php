<?php

namespace Dev3bdulrahman\LaravelCaptcha\Tests;

use Orchestra\Testbench\TestCase as Orchestra;
use Dev3bdulrahman\LaravelCaptcha\CaptchaServiceProvider;

class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();
    }

    protected function getPackageProviders($app)
    {
        return [
            CaptchaServiceProvider::class,
        ];
    }

    protected function getPackageAliases($app)
    {
        return [
            'Captcha' => \Dev3bdulrahman\LaravelCaptcha\Facades\Captcha::class,
        ];
    }

    protected function getEnvironmentSetUp($app)
    {
        // Setup default configuration
        $app['config']->set('captcha.default', 'image');
        $app['config']->set('captcha.difficulty', 'medium');
        $app['config']->set('session.driver', 'array');
    }
}

