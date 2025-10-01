<?php

namespace Dev3bdulrahman\LaravelCaptcha\Tests\Feature;

use Dev3bdulrahman\LaravelCaptcha\Tests\TestCase;
use Dev3bdulrahman\LaravelCaptcha\Facades\Captcha;
use Illuminate\Support\Facades\Session;

class CaptchaTest extends TestCase
{
    /** @test */
    public function it_can_generate_image_captcha()
    {
        $data = Captcha::generate('image', 'medium');

        $this->assertIsArray($data);
        $this->assertEquals('image', $data['type']);
        $this->assertArrayHasKey('value', $data);
        $this->assertArrayHasKey('image_url', $data);
    }

    /** @test */
    public function it_can_generate_math_captcha()
    {
        $data = Captcha::generate('math', 'easy');

        $this->assertIsArray($data);
        $this->assertEquals('math', $data['type']);
        $this->assertArrayHasKey('question', $data);
        $this->assertArrayHasKey('value', $data);
    }

    /** @test */
    public function it_can_generate_text_captcha()
    {
        $data = Captcha::generate('text', 'medium');

        $this->assertIsArray($data);
        $this->assertEquals('text', $data['type']);
        $this->assertArrayHasKey('question', $data);
        $this->assertArrayHasKey('value', $data);
    }

    /** @test */
    public function it_can_generate_slider_captcha()
    {
        $data = Captcha::generate('slider', 'hard');

        $this->assertIsArray($data);
        $this->assertEquals('slider', $data['type']);
        $this->assertArrayHasKey('position', $data);
        $this->assertArrayHasKey('value', $data);
    }

    /** @test */
    public function it_can_verify_correct_captcha()
    {
        $data = Captcha::generate('image', 'medium');
        $result = Captcha::verify($data['value'], 'image');

        $this->assertTrue($result);
    }

    /** @test */
    public function it_rejects_incorrect_captcha()
    {
        Captcha::generate('image', 'medium');
        $result = Captcha::verify('wrong_value', 'image');

        $this->assertFalse($result);
    }

    /** @test */
    public function it_stores_captcha_in_session()
    {
        Captcha::generate('image', 'medium');

        $sessionKey = config('captcha.session_key', 'laravel_captcha');
        $this->assertTrue(Session::has("{$sessionKey}.image"));
    }

    /** @test */
    public function it_clears_captcha_after_successful_verification()
    {
        $data = Captcha::generate('image', 'medium');
        Captcha::verify($data['value'], 'image');

        $sessionKey = config('captcha.session_key', 'laravel_captcha');
        $this->assertFalse(Session::has("{$sessionKey}.image"));
    }

    /** @test */
    public function it_can_refresh_captcha()
    {
        $data1 = Captcha::generate('image', 'medium');
        Captcha::refresh('image');
        $data2 = Captcha::generate('image', 'medium');

        $this->assertNotEquals($data1['value'], $data2['value']);
    }

    /** @test */
    public function it_respects_difficulty_levels()
    {
        $easy = Captcha::generate('image', 'easy');
        $medium = Captcha::generate('image', 'medium');
        $hard = Captcha::generate('image', 'hard');

        $this->assertEquals('easy', $easy['difficulty']);
        $this->assertEquals('medium', $medium['difficulty']);
        $this->assertEquals('hard', $hard['difficulty']);
    }

    /** @test */
    public function math_captcha_generates_correct_answers()
    {
        $data = Captcha::generate('math', 'easy');
        
        // Extract numbers and operator from question
        preg_match('/(\d+)\s*([+\-*\/])\s*(\d+)/', $data['question'], $matches);
        
        if (count($matches) === 4) {
            $num1 = (int) $matches[1];
            $operator = $matches[2];
            $num2 = (int) $matches[3];
            
            $expected = match($operator) {
                '+' => $num1 + $num2,
                '-' => $num1 - $num2,
                '*' => $num1 * $num2,
                '/' => $num1 / $num2,
                default => 0,
            };
            
            $this->assertEquals((string) $expected, $data['value']);
        }
    }

    /** @test */
    public function it_handles_case_insensitive_verification()
    {
        config(['captcha.case_sensitive' => false]);
        
        $data = Captcha::generate('image', 'medium');
        $result = Captcha::verify(strtolower($data['value']), 'image');

        $this->assertTrue($result);
    }
}

