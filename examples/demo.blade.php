<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Laravel Captcha Demo</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 40px 20px;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
        }

        .header {
            text-align: center;
            color: white;
            margin-bottom: 50px;
        }

        .header h1 {
            font-size: 48px;
            margin-bottom: 10px;
        }

        .header p {
            font-size: 18px;
            opacity: 0.9;
        }

        .demo-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
            gap: 30px;
            margin-bottom: 40px;
        }

        .demo-card {
            background: white;
            border-radius: 12px;
            padding: 30px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
        }

        .demo-card h2 {
            margin-bottom: 20px;
            color: #333;
            font-size: 24px;
        }

        .demo-card form {
            margin-top: 20px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: #555;
        }

        .form-group input[type="text"],
        .form-group input[type="email"],
        .form-group textarea {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #e0e0e0;
            border-radius: 6px;
            font-size: 16px;
            transition: all 0.3s ease;
        }

        .form-group input:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: #667eea;
        }

        .btn {
            width: 100%;
            padding: 14px 20px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 6px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: transform 0.2s ease;
        }

        .btn:hover {
            transform: translateY(-2px);
        }

        .alert {
            padding: 12px 15px;
            border-radius: 6px;
            margin-bottom: 20px;
        }

        .alert-success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .alert-error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        .footer {
            text-align: center;
            color: white;
            margin-top: 50px;
        }

        .footer a {
            color: white;
            text-decoration: none;
            font-weight: 600;
        }

        @media (max-width: 768px) {
            .demo-grid {
                grid-template-columns: 1fr;
            }

            .header h1 {
                font-size: 32px;
            }
        }
    </style>
    @stack('styles')
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🔒 Laravel Captcha Demo</h1>
            <p>Test all captcha types and difficulty levels</p>
        </div>

        <div class="demo-grid">
            <!-- Image Captcha -->
            <div class="demo-card">
                <h2>📸 Image Captcha</h2>
                <p style="color: #666; margin-bottom: 20px;">Classic image-based captcha with distorted text</p>
                
                <form method="POST" action="/demo/verify">
                    @csrf
                    <input type="hidden" name="type" value="image">
                    
                    @include('captcha::captcha', [
                        'type' => 'image',
                        'difficulty' => 'medium',
                        'style' => 'modern'
                    ])
                    
                    <button type="submit" class="btn">Verify Image Captcha</button>
                </form>
            </div>

            <!-- Math Captcha -->
            <div class="demo-card">
                <h2>🔢 Math Captcha</h2>
                <p style="color: #666; margin-bottom: 20px;">Solve simple math problems</p>
                
                <form method="POST" action="/demo/verify">
                    @csrf
                    <input type="hidden" name="type" value="math">
                    
                    @include('captcha::captcha', [
                        'type' => 'math',
                        'difficulty' => 'easy',
                        'style' => 'colorful'
                    ])
                    
                    <button type="submit" class="btn">Verify Math Captcha</button>
                </form>
            </div>

            <!-- Text Captcha -->
            <div class="demo-card">
                <h2>💬 Text Captcha</h2>
                <p style="color: #666; margin-bottom: 20px;">Answer simple questions</p>
                
                <form method="POST" action="/demo/verify">
                    @csrf
                    <input type="hidden" name="type" value="text">
                    
                    @include('captcha::captcha', [
                        'type' => 'text',
                        'difficulty' => 'medium',
                        'style' => 'default'
                    ])
                    
                    <button type="submit" class="btn">Verify Text Captcha</button>
                </form>
            </div>

            <!-- Slider Captcha -->
            <div class="demo-card">
                <h2>🎯 Slider Captcha</h2>
                <p style="color: #666; margin-bottom: 20px;">Slide to complete the puzzle</p>
                
                <form method="POST" action="/demo/verify">
                    @csrf
                    <input type="hidden" name="type" value="slider">
                    
                    @include('captcha::captcha', [
                        'type' => 'slider',
                        'difficulty' => 'medium',
                        'style' => 'modern'
                    ])
                    
                    <button type="submit" class="btn">Verify Slider Captcha</button>
                </form>
            </div>
        </div>

        <!-- Contact Form Example -->
        <div class="demo-card" style="max-width: 600px; margin: 0 auto;">
            <h2>📧 Contact Form Example</h2>
            <p style="color: #666; margin-bottom: 20px;">Complete form with captcha protection</p>
            
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            @if($errors->any())
                <div class="alert alert-error">
                    <ul style="margin-left: 20px;">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            
            <form method="POST" action="/demo/contact">
                @csrf
                
                <div class="form-group">
                    <label for="name">Name</label>
                    <input type="text" id="name" name="name" required>
                </div>

                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" required>
                </div>

                <div class="form-group">
                    <label for="message">Message</label>
                    <textarea id="message" name="message" rows="4" required></textarea>
                </div>
                
                @include('captcha::captcha', [
                    'type' => 'image',
                    'difficulty' => 'medium',
                    'style' => 'modern'
                ])
                
                <button type="submit" class="btn">Send Message</button>
            </form>
        </div>

        <div class="footer">
            <p>Made with ❤️ by <a href="https://3bdulrahman.com/" target="_blank">Abdulrahman Mehesan</a></p>
            <p style="margin-top: 10px;">
                <a href="https://github.com/Dev-3bdulrahman/Laravel-Captcha" target="_blank">⭐ Star on GitHub</a>
            </p>
        </div>
    </div>

    @stack('scripts')
</body>
</html>

