<?php

/**
 * SVG Captcha Example
 * 
 * This example shows how to use SVG captcha instead of PNG
 * which doesn't require GD Library.
 */

// 1. Enable SVG in your configuration
// config/captcha.php
return [
    'image' => [
        'use_svg' => true, // Enable SVG format
        'width' => 200,
        'height' => 60,
        // ... other settings
    ],
];

// 2. Or set environment variable
// .env
// CAPTCHA_USE_SVG=true

// 3. Use in your Blade template
?>
<!DOCTYPE html>
<html>
<head>
    <title>SVG Captcha Example</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>
    <h1>SVG Captcha Example</h1>
    
    <form method="POST" action="/submit">
        @csrf
        
        <div>
            <label>Name:</label>
            <input type="text" name="name" required>
        </div>
        
        <div>
            <label>Email:</label>
            <input type="email" name="email" required>
        </div>
        
        <!-- SVG Captcha - No GD Library required! -->
        @include('captcha::captcha', [
            'type' => 'image',
            'difficulty' => 'medium',
            'style' => 'modern'
        ])
        
        @error('captcha')
            <div style="color: red;">{{ $message }}</div>
        @enderror
        
        <button type="submit">Submit</button>
    </form>
    
    <script>
        // Refresh captcha function
        function refreshCaptcha() {
            fetch('/captcha/refresh?type=image')
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Reload the captcha image
                        const img = document.querySelector('.captcha-image');
                        if (img) {
                            img.src = data.data.image_url + '&t=' + Date.now();
                        }
                    }
                });
        }
    </script>
</body>
</html>

<?php
// 4. Controller validation
class ContactController extends Controller
{
    public function submit(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'captcha' => 'required|captcha', // This works with both PNG and SVG
        ]);
        
        // Process form...
        return back()->with('success', 'Form submitted successfully!');
    }
}

// 5. Manual verification (if needed)
use Dev3bdulrahman\LaravelCaptcha\Facades\Captcha;

if (Captcha::verify($request->input('captcha'), 'image')) {
    // Captcha is valid - works with both PNG and SVG
} else {
    // Captcha is invalid
}

// 6. Generate captcha programmatically
$data = Captcha::generate('image', 'medium');
// Returns:
// [
//     'type' => 'image',
//     'value' => 'ABC123',
//     'difficulty' => 'medium',
//     'image_url' => 'http://example.com/captcha/svg/image?difficulty=medium',
//     'format' => 'svg'
// ]

// 7. Benefits of SVG Captcha:
// - No GD Library dependency
// - Scalable vector graphics
// - Smaller file size
// - Better performance
// - Works on all modern browsers
// - Easier to style with CSS
// - Accessible and SEO-friendly
?>