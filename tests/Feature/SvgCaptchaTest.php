<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Support\Facades\Config;
use Dev3bdulrahman\LaravelCaptcha\Generators\ImageCaptchaGenerator;

class SvgCaptchaTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        
        // Enable SVG for testing
        Config::set('captcha.image.use_svg', true);
    }

    /** @test */
    public function it_can_generate_svg_captcha()
    {
        $generator = new ImageCaptchaGenerator('medium');
        $data = $generator->generate();

        $this->assertArrayHasKey('type', $data);
        $this->assertArrayHasKey('value', $data);
        $this->assertArrayHasKey('difficulty', $data);
        $this->assertArrayHasKey('image_url', $data);
        $this->assertArrayHasKey('format', $data);
        
        $this->assertEquals('image', $data['type']);
        $this->assertEquals('medium', $data['difficulty']);
        $this->assertEquals('svg', $data['format']);
    }

    /** @test */
    public function it_can_generate_svg_image_response()
    {
        $generator = new ImageCaptchaGenerator('medium');
        
        // Generate captcha data first
        $data = $generator->generate();
        
        // Store in session
        session(['laravel_captcha.image' => [
            'value' => $data['value'],
            'expires_at' => now()->addMinutes(5),
        ]]);

        $response = $generator->svg();

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('image/svg+xml', $response->headers->get('Content-Type'));
        
        $content = $response->getContent();
        $this->assertStringContainsString('<svg', $content);
        $this->assertStringContainsString('</svg>', $content);
        $this->assertStringContainsString('<text', $content);
    }

    /** @test */
    public function svg_captcha_contains_expected_elements()
    {
        $generator = new ImageCaptchaGenerator('medium');
        
        // Generate captcha data first
        $data = $generator->generate();
        
        // Store in session
        session(['laravel_captcha.image' => [
            'value' => $data['value'],
            'expires_at' => now()->addMinutes(5),
        ]]);

        $response = $generator->svg();
        $content = $response->getContent();

        // Check for SVG structure
        $this->assertStringContainsString('<svg width="200" height="60"', $content);
        $this->assertStringContainsString('<rect width="200" height="60"', $content);
        
        // Check for noise elements
        $this->assertStringContainsString('<line', $content);
        $this->assertStringContainsString('<circle', $content);
        
        // Check for text elements
        $this->assertStringContainsString('<text', $content);
        $this->assertStringContainsString('font-family="Arial, sans-serif"', $content);
    }

    /** @test */
    public function it_falls_back_to_png_when_svg_disabled()
    {
        Config::set('captcha.image.use_svg', false);
        
        $generator = new ImageCaptchaGenerator('medium');
        $data = $generator->generate();

        $this->assertEquals('png', $data['format']);
        $this->assertStringContainsString('captcha/image', $data['image_url']);
    }

    /** @test */
    public function svg_route_returns_svg_content()
    {
        $response = $this->get(route('captcha.svg', ['type' => 'image', 'difficulty' => 'medium']));

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'image/svg+xml');
        
        $content = $response->getContent();
        $this->assertStringContainsString('<svg', $content);
        $this->assertStringContainsString('</svg>', $content);
    }
}