# Installation & Testing Guide

This guide will help you install and test the Laravel Captcha package.

## Prerequisites

Before installing, make sure you have:

- PHP 8.0 or higher
- Laravel 9.x, 10.x, or 11.x
- Composer
- GD Library (for image captcha)

### Check GD Library

```bash
php -m | grep -i gd
```

If GD is not installed:

**Ubuntu/Debian:**
```bash
sudo apt-get install php-gd
```

**macOS:**
```bash
brew install php-gd
```

**Windows:**
Enable `extension=gd` in your `php.ini` file.

## Installation Steps

### 1. Install via Composer

```bash
composer require dev-3bdulrahman/laravel-captcha
```

### 2. Publish Configuration

```bash
php artisan vendor:publish --tag=captcha-config
```

This will create `config/captcha.php` where you can customize all settings.

### 3. Publish Assets

```bash
php artisan vendor:publish --tag=captcha-assets
```

This will publish CSS and JavaScript files to `public/vendor/captcha/`.

### 4. (Optional) Publish Views

If you want to customize the views:

```bash
php artisan vendor:publish --tag=captcha-views
```

This will publish views to `resources/views/vendor/captcha/`.

## Testing the Package

### 1. Create a Test Route

Add to `routes/web.php`:

```php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/captcha-test', function () {
    return view('captcha-test');
});

Route::post('/captcha-test', function (Request $request) {
    $request->validate([
        'captcha' => 'required|captcha',
    ]);
    
    return back()->with('success', 'Captcha verified successfully!');
})->name('captcha.test.submit');
```

### 2. Create a Test View

Create `resources/views/captcha-test.blade.php`:

```blade
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Captcha Test</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
        }
        .alert {
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 4px;
        }
        .alert-success {
            background: #d4edda;
            color: #155724;
        }
        .alert-error {
            background: #f8d7da;
            color: #721c24;
        }
        button {
            padding: 10px 20px;
            background: #667eea;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
    </style>
    @stack('styles')
</head>
<body>
    <h1>Laravel Captcha Test</h1>
    
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if($errors->any())
        <div class="alert alert-error">
            @foreach($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif

    <form method="POST" action="{{ route('captcha.test.submit') }}">
        @csrf
        
        <h2>Image Captcha</h2>
        @include('captcha::captcha', ['type' => 'image', 'difficulty' => 'medium'])
        
        <br><br>
        
        <h2>Math Captcha</h2>
        @include('captcha::captcha', ['type' => 'math', 'difficulty' => 'easy'])
        
        <br><br>
        
        <h2>Text Captcha</h2>
        @include('captcha::captcha', ['type' => 'text', 'difficulty' => 'medium'])
        
        <br><br>
        
        <button type="submit">Test Captcha</button>
    </form>

    @stack('scripts')
</body>
</html>
```

### 3. Visit the Test Page

Open your browser and navigate to:

```
http://your-app.test/captcha-test
```

### 4. Run Unit Tests

```bash
composer test
```

Or with PHPUnit directly:

```bash
./vendor/bin/phpunit
```

## Troubleshooting

### Issue: Captcha image not showing

**Solution:**
1. Make sure GD library is installed
2. Check file permissions on `storage/` directory
3. Clear cache: `php artisan cache:clear`

### Issue: "Class 'Captcha' not found"

**Solution:**
1. Run `composer dump-autoload`
2. Clear config cache: `php artisan config:clear`

### Issue: Assets not loading

**Solution:**
1. Make sure you published assets: `php artisan vendor:publish --tag=captcha-assets`
2. Check if files exist in `public/vendor/captcha/`
3. Run `php artisan storage:link` if needed

### Issue: Session errors

**Solution:**
1. Make sure session is configured properly in `config/session.php`
2. Check if session middleware is applied to your routes
3. Clear session: `php artisan session:flush`

## Advanced Configuration

### Custom Fonts for Image Captcha

1. Download TTF fonts
2. Place them in `resources/fonts/`
3. Update `config/captcha.php`:

```php
'image' => [
    'fonts' => [
        'YourFont-Bold.ttf',
        'AnotherFont-Bold.ttf',
    ],
],
```

### Custom Questions for Text Captcha

Edit `config/captcha.php`:

```php
'text' => [
    'questions' => [
        'easy' => [
            'What is your custom question?' => 'answer',
        ],
    ],
],
```

### Disable Routes

If you want to register routes manually:

```php
// config/captcha.php
'routes' => [
    'enabled' => false,
],
```

Then register routes manually in your `routes/web.php`.

## Performance Tips

1. **Use appropriate difficulty levels**: Don't use 'hard' for all forms
2. **Cache configuration**: Run `php artisan config:cache` in production
3. **Optimize assets**: Minify CSS/JS files
4. **Use CDN**: Serve static assets from CDN

## Security Best Practices

1. Always validate captcha on server-side
2. Set appropriate expiration time
3. Use HTTPS in production
4. Implement rate limiting
5. Monitor for abuse patterns

## Next Steps

- Read the [Full Documentation](README.md)
- Check out [Examples](examples/)
- Customize the [Configuration](config/captcha.php)
- Join [Discussions](https://github.com/Dev-3bdulrahman/Laravel-Captcha/discussions)

---

Need help? Open an issue on [GitHub](https://github.com/Dev-3bdulrahman/Laravel-Captcha/issues)

