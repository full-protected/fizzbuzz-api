<?php

declare(strict_types=1);

namespace App\Tests\Functional;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class FizzBuzzApiTest extends WebTestCase
{
    public function testSuccessfulRequest(): void
    {
        $client = static::createClient();
        $client->request('GET', '/api/fizzbuzz?int1=3&int2=5&limit=16&str1=fizz&str2=buzz');

        self::assertResponseIsSuccessful();
        self::assertJsonStringEqualsJsonString(
            '{"result":["1","2","fizz","4","buzz","fizz","7","8","fizz","buzz","11","fizz","13","14","fizzbuzz","16"]}',
            $client->getResponse()->getContent(),
        );
    }

    public function testMissingParameterReturnsValidationError(): void
    {
        $client = static::createClient();
        $client->request('GET', '/api/fizzbuzz?int1=3&int2=5&limit=16&str1=fizz');

        self::assertResponseStatusCodeSame(422);
    }

    public function testNegativeInt1ReturnsValidationError(): void
    {
        $client = static::createClient();
        $client->request('GET', '/api/fizzbuzz?int1=-3&int2=5&limit=16&str1=fizz&str2=buzz');

        self::assertResponseStatusCodeSame(422);
    }

    public function testZeroInt2ReturnsValidationError(): void
    {
        $client = static::createClient();
        $client->request('GET', '/api/fizzbuzz?int1=3&int2=0&limit=16&str1=fizz&str2=buzz');

        self::assertResponseStatusCodeSame(422);
    }

    public function testLimitAboveMaximumReturnsValidationError(): void
    {
        $client = static::createClient();
        $client->request('GET', '/api/fizzbuzz?int1=3&int2=5&limit=100001&str1=fizz&str2=buzz');

        self::assertResponseStatusCodeSame(422);
    }

    public function testEmptyStr1ReturnsValidationError(): void
    {
        $client = static::createClient();
        $client->request('GET', '/api/fizzbuzz?int1=3&int2=5&limit=16&str1=&str2=buzz');

        self::assertResponseStatusCodeSame(422);
    }

    public function testEmptyStr2ReturnsValidationError(): void
    {
        $client = static::createClient();
        $client->request('GET', '/api/fizzbuzz?int1=3&int2=5&limit=16&str1=fizz&str2=');

        self::assertResponseStatusCodeSame(422);
    }

    public function testNonNumericLimitReturnsValidationError(): void
    {
        $client = static::createClient();
        $client->request('GET', '/api/fizzbuzz?int1=3&int2=5&limit=abc&str1=fizz&str2=buzz');

        self::assertResponseStatusCodeSame(422);
    }
}
