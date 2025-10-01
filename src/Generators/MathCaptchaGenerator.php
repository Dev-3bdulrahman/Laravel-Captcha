<?php

namespace Dev3bdulrahman\LaravelCaptcha\Generators;

class MathCaptchaGenerator extends CaptchaGenerator
{
    /**
     * Generate math captcha.
     *
     * @return array
     */
    public function generate(): array
    {
        $config = config("captcha.math");
        $operators = $config['operators'][$this->difficulty];
        $range = $config['range'][$this->difficulty];

        $operator = $operators[array_rand($operators)];
        $num1 = random_int($range[0], $range[1]);
        $num2 = random_int($range[0], $range[1]);

        // Ensure division results in whole numbers
        if ($operator === '/') {
            $num1 = $num2 * random_int(1, 10);
        }

        // Ensure subtraction doesn't result in negative numbers for easy/medium
        if ($operator === '-' && $this->difficulty !== 'hard' && $num2 > $num1) {
            [$num1, $num2] = [$num2, $num1];
        }

        $question = "{$num1} {$operator} {$num2} = ?";
        $answer = $this->calculate($num1, $num2, $operator);

        return [
            'type' => 'math',
            'question' => $question,
            'value' => (string) $answer,
            'difficulty' => $this->difficulty,
        ];
    }

    /**
     * Calculate the result.
     *
     * @param int $num1
     * @param int $num2
     * @param string $operator
     * @return int
     */
    protected function calculate(int $num1, int $num2, string $operator): int
    {
        return match ($operator) {
            '+' => $num1 + $num2,
            '-' => $num1 - $num2,
            '*' => $num1 * $num2,
            '/' => $num1 / $num2,
            default => 0,
        };
    }
}

