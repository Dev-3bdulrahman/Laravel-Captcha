<?php

namespace Dev3bdulrahman\LaravelCaptcha\Generators;

use Illuminate\Support\Facades\Session;

class ImageCaptchaGenerator extends CaptchaGenerator
{
    /**
     * Generate image captcha.
     *
     * @return array
     */
    public function generate(): array
    {
        $config = config('captcha.image');
        $length = $config['length'][$this->difficulty];
        $characters = $config['characters'][$this->difficulty];

        $code = $this->randomString($length, $characters);

        return [
            'type' => 'image',
            'value' => $code,
            'difficulty' => $this->difficulty,
            'image_url' => route('captcha.image', ['type' => 'image', 'difficulty' => $this->difficulty]),
        ];
    }

    /**
     * Generate captcha image.
     *
     * @return \Illuminate\Http\Response
     */
    public function image()
    {
        $config = config('captcha.image');
        $sessionKey = config('captcha.session_key', 'laravel_captcha');
        
        // Get code from session
        $captchaData = Session::get("{$sessionKey}.image");
        
        if (!$captchaData) {
            // Generate new code if not in session
            $data = $this->generate();
            $code = $data['value'];
        } else {
            $code = $captchaData['value'];
        }

        $width = $config['width'];
        $height = $config['height'];

        // Create image
        $image = imagecreatetruecolor($width, $height);

        // Set colors
        $bgColor = imagecolorallocate($image, ...$config['colors']['background']);
        $textColor = imagecolorallocate($image, ...$config['colors']['text']);
        $noiseColor = imagecolorallocate($image, ...$config['colors']['noise']);

        // Fill background
        imagefilledrectangle($image, 0, 0, $width, $height, $bgColor);

        // Add noise lines
        $lines = $config['lines'][$this->difficulty];
        for ($i = 0; $i < $lines; $i++) {
            $color = imagecolorallocate($image, rand(150, 225), rand(150, 225), rand(150, 225));
            imageline($image, rand(0, $width), rand(0, $height), rand(0, $width), rand(0, $height), $color);
        }

        // Add noise dots
        $noise = $config['noise'][$this->difficulty];
        for ($i = 0; $i < $noise; $i++) {
            $color = imagecolorallocate($image, rand(150, 225), rand(150, 225), rand(150, 225));
            imagefilledellipse($image, rand(0, $width), rand(0, $height), 3, 3, $color);
        }

        // Add text
        $fontSize = $this->getFontSize();
        $angle = 0;
        $x = 10;
        $y = $height / 2 + 10;

        $codeLength = strlen($code);
        $charWidth = ($width - 20) / $codeLength;

        for ($i = 0; $i < $codeLength; $i++) {
            $char = $code[$i];
            $charAngle = rand(-15, 15);
            $charX = $x + ($i * $charWidth) + rand(-5, 5);
            $charY = $y + rand(-10, 10);
            
            // Random color for each character
            $charColor = imagecolorallocate($image, rand(0, 100), rand(0, 100), rand(0, 100));

            // Try to use TTF font if available, otherwise use built-in font
            $fontPath = $this->getFontPath();
            
            if ($fontPath && file_exists($fontPath)) {
                imagettftext($image, $fontSize, $charAngle, $charX, $charY, $charColor, $fontPath, $char);
            } else {
                // Fallback to built-in font
                imagestring($image, 5, $charX, $charY - 20, $char, $charColor);
            }
        }

        // Output image
        ob_start();
        imagepng($image);
        $imageData = ob_get_clean();
        imagedestroy($image);

        return response($imageData)
            ->header('Content-Type', 'image/png')
            ->header('Cache-Control', 'no-cache, no-store, must-revalidate')
            ->header('Pragma', 'no-cache')
            ->header('Expires', '0');
    }

    /**
     * Get font size based on difficulty.
     *
     * @return int
     */
    protected function getFontSize(): int
    {
        return match ($this->difficulty) {
            'easy' => 24,
            'medium' => 20,
            'hard' => 18,
            default => 20,
        };
    }

    /**
     * Get font path.
     *
     * @return string|null
     */
    protected function getFontPath(): ?string
    {
        $fonts = config('captcha.image.fonts', []);
        
        if (empty($fonts)) {
            return null;
        }

        $font = $fonts[array_rand($fonts)];
        $fontPath = __DIR__ . '/../../resources/fonts/' . $font;

        return file_exists($fontPath) ? $fontPath : null;
    }
}

