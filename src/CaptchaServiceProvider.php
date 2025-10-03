<?php

namespace Dev3bdulrahman\LaravelCaptcha;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Validator;

class CaptchaServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/captcha.php', 'captcha'
        );

        $this->app->singleton('captcha', function ($app) {
            return new CaptchaManager($app);
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Publish configuration
        $this->publishes([
            __DIR__.'/../config/captcha.php' => config_path('captcha.php'),
        ], 'captcha-config');

        // Publish assets
        $this->publishes([
            __DIR__.'/../resources/assets' => public_path('vendor/captcha'),
        ], 'captcha-assets');

        // Publish views
        $this->publishes([
            __DIR__.'/../resources/views' => resource_path('views/vendor/captcha'),
        ], 'captcha-views');

        // Load views
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'captcha');

        // Register routes
        if (config('captcha.routes.enabled', true)) {
            $this->registerRoutes();
        }

        // Register validation rule
        $this->registerValidationRule();
    }

    /**
     * Register package routes.
     */
    protected function registerRoutes(): void
    {
        Route::group([
            'prefix' => config('captcha.routes.prefix', 'captcha'),
            'middleware' => config('captcha.routes.middleware', ['web']),
        ], function () {
            Route::get('/generate/{type?}', [CaptchaController::class, 'generate'])
                ->name('captcha.generate');
            Route::get('/image/{type?}', [CaptchaController::class, 'image'])
                ->name('captcha.image');
            Route::get('/svg/{type?}', [CaptchaController::class, 'svg'])
                ->name('captcha.svg');
            Route::post('/verify', [CaptchaController::class, 'verify'])
                ->name('captcha.verify');
            Route::get('/refresh', [CaptchaController::class, 'refresh'])
                ->name('captcha.refresh');
        });
    }

    /**
     * Register custom validation rule.
     */
    protected function registerValidationRule(): void
    {
        Validator::extend('captcha', function ($attribute, $value, $parameters, $validator) {
            $type = $parameters[0] ?? config('captcha.default');

            // Log: Validation rule called
            \Log::info('🔐 CAPTCHA VALIDATION RULE CALLED', [
                'attribute' => $attribute,
                'value' => $value,
                'type' => $type,
                'parameters' => $parameters,
                'default_type' => config('captcha.default')
            ]);

            $result = app('captcha')->verify($value, $type);

            // Log: Validation result
            \Log::info('🔐 CAPTCHA VALIDATION RESULT', [
                'result' => $result ? 'PASS' : 'FAIL',
                'value' => $value,
                'type' => $type
            ]);

            return $result;
        }, 'The captcha verification failed.');

        Validator::replacer('captcha', function ($message, $attribute, $rule, $parameters) {
            return str_replace(':attribute', $attribute, $message);
        });
    }
}

