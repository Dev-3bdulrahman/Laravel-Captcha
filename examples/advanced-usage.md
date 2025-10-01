# Advanced Usage Examples

This document provides advanced usage examples for Laravel Captcha.

## Table of Contents

1. [Custom Validation Messages](#custom-validation-messages)
2. [AJAX Integration](#ajax-integration)
3. [Multiple Captchas on Same Page](#multiple-captchas-on-same-page)
4. [Custom Styling](#custom-styling)
5. [Rate Limiting Integration](#rate-limiting-integration)
6. [API Usage](#api-usage)
7. [Custom Generators](#custom-generators)

---

## Custom Validation Messages

### In Controller

```php
public function submit(Request $request)
{
    $request->validate([
        'captcha' => 'required|captcha',
    ], [
        'captcha.required' => 'Please complete the captcha verification.',
        'captcha.captcha' => 'The captcha verification failed. Please try again.',
    ]);
}
```

### In Language Files

Create `resources/lang/en/validation.php`:

```php
'custom' => [
    'captcha' => [
        'required' => 'Please complete the captcha verification.',
        'captcha' => 'The captcha verification failed. Please try again.',
    ],
],
```

---

## AJAX Integration

### Frontend (JavaScript)

```javascript
// Submit form via AJAX
document.getElementById('myForm').addEventListener('submit', async (e) => {
    e.preventDefault();
    
    const formData = new FormData(e.target);
    
    try {
        const response = await fetch('/submit', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            },
            body: formData
        });
        
        const data = await response.json();
        
        if (data.success) {
            alert('Form submitted successfully!');
        } else {
            alert('Captcha verification failed!');
            // Refresh captcha
            refreshCaptcha('captcha-container-id');
        }
    } catch (error) {
        console.error('Error:', error);
    }
});
```

### Backend (Controller)

```php
public function submit(Request $request)
{
    $validator = Validator::make($request->all(), [
        'captcha' => 'required|captcha',
    ]);
    
    if ($validator->fails()) {
        return response()->json([
            'success' => false,
            'errors' => $validator->errors()
        ], 422);
    }
    
    // Process form
    
    return response()->json([
        'success' => true,
        'message' => 'Form submitted successfully!'
    ]);
}
```

---

## Multiple Captchas on Same Page

```blade
<!-- Login Form -->
<form id="login-form">
    @csrf
    <input type="email" name="email" required>
    <input type="password" name="password" required>
    
    @include('captcha::captcha', [
        'type' => 'math',
        'difficulty' => 'easy',
        'id' => 'login-captcha'
    ])
    
    <button type="submit">Login</button>
</form>

<!-- Register Form -->
<form id="register-form">
    @csrf
    <input type="text" name="name" required>
    <input type="email" name="email" required>
    <input type="password" name="password" required>
    
    @include('captcha::captcha', [
        'type' => 'image',
        'difficulty' => 'medium',
        'id' => 'register-captcha'
    ])
    
    <button type="submit">Register</button>
</form>
```

---

## Custom Styling

### Override CSS

```css
/* In your custom CSS file */
.captcha-container.my-custom-style .captcha-input {
    border: 3px solid #ff6b6b;
    border-radius: 10px;
    padding: 15px;
}

.captcha-container.my-custom-style .captcha-question-container {
    background: linear-gradient(135deg, #ff6b6b 0%, #feca57 100%);
}
```

### Use Custom Style

```blade
@include('captcha::captcha', [
    'type' => 'math',
    'style' => 'my-custom-style'
])
```

---

## Rate Limiting Integration

### In Routes

```php
use Illuminate\Support\Facades\RateLimiter;

Route::post('/submit', function (Request $request) {
    $key = 'captcha:' . $request->ip();
    
    if (RateLimiter::tooManyAttempts($key, 5)) {
        $seconds = RateLimiter::availableIn($key);
        return back()->withErrors([
            'captcha' => "Too many attempts. Please try again in {$seconds} seconds."
        ]);
    }
    
    $request->validate([
        'captcha' => 'required|captcha',
    ]);
    
    RateLimiter::clear($key);
    
    // Process form
})->middleware('throttle:10,1');
```

### Custom Middleware

```php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\RateLimiter;

class CaptchaRateLimit
{
    public function handle($request, Closure $next)
    {
        $key = 'captcha:' . $request->ip();
        
        if (RateLimiter::tooManyAttempts($key, 5)) {
            return response()->json([
                'error' => 'Too many captcha attempts.'
            ], 429);
        }
        
        RateLimiter::hit($key, 60);
        
        return $next($request);
    }
}
```

---

## API Usage

### Generate Captcha via API

```php
Route::get('/api/captcha/generate', function (Request $request) {
    $type = $request->get('type', 'image');
    $difficulty = $request->get('difficulty', 'medium');
    
    $data = Captcha::generate($type, $difficulty);
    
    return response()->json([
        'success' => true,
        'data' => $data
    ]);
});
```

### Verify Captcha via API

```php
Route::post('/api/captcha/verify', function (Request $request) {
    $request->validate([
        'captcha' => 'required|string',
        'type' => 'required|string',
    ]);
    
    $isValid = Captcha::verify($request->captcha, $request->type);
    
    return response()->json([
        'success' => $isValid,
        'message' => $isValid ? 'Verified' : 'Invalid captcha'
    ]);
});
```

---

## Custom Generators

### Create Custom Generator

```php
namespace App\Captcha\Generators;

use Dev3bdulrahman\LaravelCaptcha\Generators\CaptchaGenerator;

class CustomCaptchaGenerator extends CaptchaGenerator
{
    public function generate(): array
    {
        // Your custom logic here
        $code = $this->generateCustomCode();
        
        return [
            'type' => 'custom',
            'value' => $code,
            'difficulty' => $this->difficulty,
            'custom_data' => 'your custom data',
        ];
    }
    
    protected function generateCustomCode(): string
    {
        // Custom code generation logic
        return 'CUSTOM-' . rand(1000, 9999);
    }
}
```

### Register Custom Generator

```php
// In a service provider
use Dev3bdulrahman\LaravelCaptcha\CaptchaManager;

public function boot()
{
    $this->app->extend('captcha', function (CaptchaManager $manager) {
        $manager->registerGenerator('custom', \App\Captcha\Generators\CustomCaptchaGenerator::class);
        return $manager;
    });
}
```

---

## Event Listeners

### Listen for Captcha Events

```php
namespace App\Listeners;

class CaptchaVerifiedListener
{
    public function handle($event)
    {
        // Log successful verification
        \Log::info('Captcha verified', [
            'ip' => request()->ip(),
            'type' => $event->type,
        ]);
    }
}
```

---

## Testing

### Feature Test Example

```php
namespace Tests\Feature;

use Tests\TestCase;
use Dev3bdulrahman\LaravelCaptcha\Facades\Captcha;

class ContactFormTest extends TestCase
{
    public function test_contact_form_requires_captcha()
    {
        $response = $this->post('/contact', [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'message' => 'Hello',
        ]);
        
        $response->assertSessionHasErrors('captcha');
    }
    
    public function test_contact_form_accepts_valid_captcha()
    {
        $data = Captcha::generate('math', 'easy');
        
        $response = $this->post('/contact', [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'message' => 'Hello',
            'captcha' => $data['value'],
        ]);
        
        $response->assertSessionHasNoErrors();
    }
}
```

---

For more examples and documentation, visit the [GitHub repository](https://github.com/Dev-3bdulrahman/Laravel-Captcha).

