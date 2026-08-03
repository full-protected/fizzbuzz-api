<?php

declare(strict_types=1);

namespace App\Tests\Unit\Service;

use App\Service\FizzBuzzService;
use PHPUnit\Framework\TestCase;

final class FizzBuzzServiceTest extends TestCase
{
    private FizzBuzzService $fizzBuzzService;

    protected function setUp(): void
    {
        $this->fizzBuzzService = new FizzBuzzService();
    }

    public function testClassicFizzBuzz(): void
    {
        $result = $this->fizzBuzzService->generate(3, 5, 16, 'fizz', 'buzz');

        self::assertSame(
            ['1', '2', 'fizz', '4', 'buzz', 'fizz', '7', '8', 'fizz', 'buzz', '11', 'fizz', '13', '14', 'fizzbuzz', '16'],
            $result,
        );
    }

    public function testCustomDivisors(): void
    {
        $result = $this->fizzBuzzService->generate(2, 7, 14, 'foo', 'bar');

        self::assertSame(
            ['1', 'foo', '3', 'foo', '5', 'foo', 'bar', 'foo', '9', 'foo', '11', 'foo', '13', 'foobar'],
            $result,
        );
    }

    public function testCustomStrings(): void
    {
        $result = $this->fizzBuzzService->generate(3, 5, 5, 'hello', 'world');

        self::assertSame(['1', '2', 'hello', '4', 'world'], $result);
    }

    public function testLimitEqualsOne(): void
    {
        $result = $this->fizzBuzzService->generate(3, 5, 1, 'fizz', 'buzz');

        self::assertSame(['1'], $result);
    }

    public function testCommonMultiple(): void
    {
        $result = $this->fizzBuzzService->generate(3, 5, 15, 'fizz', 'buzz');

        self::assertSame('fizzbuzz', $result[14]);
    }

    public function testResultHasExpectedCount(): void
    {
        $result = $this->fizzBuzzService->generate(3, 5, 100, 'fizz', 'buzz');

        self::assertCount(100, $result);
    }
}
