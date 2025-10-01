<?php

namespace Dev3bdulrahman\LaravelCaptcha\Generators;

class TextCaptchaGenerator extends CaptchaGenerator
{
    /**
     * Generate text captcha.
     *
     * @return array
     */
    public function generate(): array
    {
        $questions = config("captcha.text.questions.{$this->difficulty}", []);

        if (empty($questions)) {
            throw new \RuntimeException("No questions configured for difficulty level: {$this->difficulty}");
        }

        $question = array_rand($questions);
        $answer = $questions[$question];

        return [
            'type' => 'text',
            'question' => $question,
            'value' => strtolower($answer),
            'difficulty' => $this->difficulty,
        ];
    }
}

