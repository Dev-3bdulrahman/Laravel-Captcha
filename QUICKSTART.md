# Quick Start Guide

Get started with Laravel Captcha in less than 5 minutes!

## Installation

```bash
composer require dev-3bdulrahman/laravel-captcha
```

## Publish Assets

```bash
php artisan vendor:publish --tag=captcha-config
php artisan vendor:publish --tag=captcha-assets
```

## Basic Usage

### 1. Add to Your Blade View

```blade
<form method="POST" action="/submit">
    @csrf
    
    <!-- Your form fields here -->
    
    @include('captcha::captcha')
    
    <button type="submit">Submit</button>
</form>
```

### 2. Validate in Controller

```php
public function submit(Request $request)
{
    $request->validate([
        'captcha' => 'required|captcha',
        // other rules...
    ]);
    
    // Your logic here
}
```

## That's It! 🎉

Your form is now protected with captcha!

## Different Captcha Types

### Image Captcha
```blade
@include('captcha::captcha', ['type' => 'image'])
```

### Math Captcha
```blade
@include('captcha::captcha', ['type' => 'math'])
```

### Text Captcha
```blade
@include('captcha::captcha', ['type' => 'text'])
```

### Slider Captcha
```blade
@include('captcha::captcha', ['type' => 'slider'])
```

## Difficulty Levels

```blade
@include('captcha::captcha', [
    'type' => 'image',
    'difficulty' => 'easy'  // easy, medium, hard
])
```

## Visual Styles

```blade
@include('captcha::captcha', [
    'type' => 'image',
    'style' => 'modern'  // default, modern, minimal, colorful
])
```

## Using Helpers

```php
// Generate captcha
$data = captcha('image', 'medium');

// Verify captcha
if (captcha_verify($input, 'image')) {
    // Valid
}

// Get image URL
$url = captcha_img('image', 'medium');
```

## Using Facade

```php
use Dev3bdulrahman\LaravelCaptcha\Facades\Captcha;

// Generate
$data = Captcha::generate('math', 'easy');

// Verify
$isValid = Captcha::verify($input, 'math');

// Refresh
Captcha::refresh('math');
```

## Configuration

Edit `config/captcha.php` to customize:

- Default captcha type
- Difficulty level
- Expiration time
- Image settings (size, colors, fonts)
- Math operators
- Text questions
- And more!

## Need Help?

- 📖 [Full Documentation](README.md)
- 🐛 [Report Issues](https://github.com/Dev-3bdulrahman/Laravel-Captcha/issues)
- 💬 [Discussions](https://github.com/Dev-3bdulrahman/Laravel-Captcha/discussions)

---

Made with ❤️ by [Abdulrahman Mehesan](https://3bdulrahman.com/)

