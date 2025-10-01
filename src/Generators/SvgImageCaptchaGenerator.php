<?php

namespace Dev3bdulrahman\LaravelCaptcha\Generators;

use Illuminate\Support\Facades\Session;

class SvgImageCaptchaGenerator extends CaptchaGenerator
{
    /**
     * Generate SVG image captcha.
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
            'type' => 'svg_image',
            'value' => $code,
            'difficulty' => $this->difficulty,
            'image_url' => route('captcha.svg', ['type' => 'svg_image', 'difficulty' => $this->difficulty]),
        ];
    }

    /**
     * Generate SVG captcha image.
     *
     * @return \Illuminate\Http\Response
     */
    public function svg()
    {
        $config = config('captcha.image');
        $sessionKey = config('captcha.session_key', 'laravel_captcha');
        
        // Get code from session
        $captchaData = Session::get("{$sessionKey}.svg_image");
        
        if (!$captchaData) {
            // Generate new code if not in session
            $data = $this->generate();
            $code = $data['value'];
        } else {
            $code = $captchaData['value'];
        }

        $width = $config['width'];
        $height = $config['height'];

        // Generate SVG
        $svg = $this->generateSvg($code, $width, $height, $config);

        return response($svg)
            ->header('Content-Type', 'image/svg+xml')
            ->header('Cache-Control', 'no-cache, no-store, must-revalidate')
            ->header('Pragma', 'no-cache')
            ->header('Expires', '0');
    }

    /**
     * Generate SVG content.
     *
     * @param string $code
     * @param int $width
     * @param int $height
     * @param array $config
     * @return string
     */
    protected function generateSvg(string $code, int $width, int $height, array $config): string
    {
        $bgColor = $this->rgbToHex($config['colors']['background']);
        
        $svg = "<svg width=\"{$width}\" height=\"{$height}\" xmlns=\"http://www.w3.org/2000/svg\">";
        
        // Background
        $svg .= "<rect width=\"{$width}\" height=\"{$height}\" fill=\"{$bgColor}\"/>";
        
        // Add noise lines
        $svg .= $this->generateNoiseLines($width, $height, $config);
        
        // Add noise dots
        $svg .= $this->generateNoiseDots($width, $height, $config);
        
        // Add text
        $svg .= $this->generateText($code, $width, $height, $config);
        
        $svg .= "</svg>";
        
        return $svg;
    }

    /**
     * Generate noise lines.
     *
     * @param int $width
     * @param int $height
     * @param array $config
     * @return string
     */
    protected function generateNoiseLines(int $width, int $height, array $config): string
    {
        $lines = '';
        $lineCount = $config['lines'][$this->difficulty];
        
        for ($i = 0; $i < $lineCount; $i++) {
            $x1 = rand(0, $width);
            $y1 = rand(0, $height);
            $x2 = rand(0, $width);
            $y2 = rand(0, $height);
            $color = $this->randomColor();
            
            $lines .= "<line x1=\"{$x1}\" y1=\"{$y1}\" x2=\"{$x2}\" y2=\"{$y2}\" stroke=\"{$color}\" stroke-width=\"1\" opacity=\"0.5\"/>";
        }
        
        return $lines;
    }

    /**
     * Generate noise dots.
     *
     * @param int $width
     * @param int $height
     * @param array $config
     * @return string
     */
    protected function generateNoiseDots(int $width, int $height, array $config): string
    {
        $dots = '';
        $dotCount = $config['noise'][$this->difficulty];
        
        for ($i = 0; $i < $dotCount; $i++) {
            $cx = rand(0, $width);
            $cy = rand(0, $height);
            $color = $this->randomColor();
            
            $dots .= "<circle cx=\"{$cx}\" cy=\"{$cy}\" r=\"2\" fill=\"{$color}\" opacity=\"0.6\"/>";
        }
        
        return $dots;
    }

    /**
     * Generate text elements.
     *
     * @param string $code
     * @param int $width
     * @param int $height
     * @param array $config
     * @return string
     */
    protected function generateText(string $code, int $width, int $height, array $config): string
    {
        $text = '';
        $codeLength = strlen($code);
        $charWidth = ($width - 20) / $codeLength;
        $fontSize = $this->getFontSize();
        
        for ($i = 0; $i < $codeLength; $i++) {
            $char = $code[$i];
            $x = 10 + ($i * $charWidth) + rand(-5, 5);
            $y = ($height / 2) + rand(-10, 10);
            $rotation = rand(-15, 15);
            $color = $this->randomDarkColor();
            
            $text .= "<text x=\"{$x}\" y=\"{$y}\" font-family=\"Arial, sans-serif\" font-size=\"{$fontSize}\" font-weight=\"bold\" fill=\"{$color}\" transform=\"rotate({$rotation} {$x} {$y})\">{$char}</text>";
        }
        
        return $text;
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
     * Convert RGB array to hex color.
     *
     * @param array $rgb
     * @return string
     */
    protected function rgbToHex(array $rgb): string
    {
        return sprintf("#%02x%02x%02x", $rgb[0], $rgb[1], $rgb[2]);
    }

    /**
     * Generate random color.
     *
     * @return string
     */
    protected function randomColor(): string
    {
        return sprintf("#%02x%02x%02x", rand(150, 225), rand(150, 225), rand(150, 225));
    }

    /**
     * Generate random dark color for text.
     *
     * @return string
     */
    protected function randomDarkColor(): string
    {
        return sprintf("#%02x%02x%02x", rand(0, 100), rand(0, 100), rand(0, 100));
    }
}