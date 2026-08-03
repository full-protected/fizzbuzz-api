<?php

declare(strict_types=1);

namespace App\Tests\Functional;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class StatisticsApiTest extends WebTestCase
{
    private function clearStatistics(): void
    {
        $container = static::getContainer();
        $entityManager = $container->get(EntityManagerInterface::class);
        $entityManager->createQuery('DELETE FROM App\Entity\Statistic')->execute();
    }

    public function testReturns404WhenNoStatisticExists(): void
    {
        $client = static::createClient();
        $this->clearStatistics();

        $client->request('GET', '/api/statistics');

        self::assertResponseStatusCodeSame(404);
    }

    public function testReturnsMostRequestedStatistic(): void
    {
        $client = static::createClient();
        $this->clearStatistics();

        $client->request('GET', '/api/fizzbuzz?int1=3&int2=5&limit=16&str1=fizz&str2=buzz');
        $client->request('GET', '/api/fizzbuzz?int1=3&int2=5&limit=16&str1=fizz&str2=buzz');
        $client->request('GET', '/api/fizzbuzz?int1=2&int2=7&limit=20&str1=foo&str2=bar');

        $client->request('GET', '/api/statistics');

        self::assertResponseIsSuccessful();
        self::assertJsonStringEqualsJsonString(
            '{"int1":3,"int2":5,"limit":16,"str1":"fizz","str2":"buzz","hits":2}',
            $client->getResponse()->getContent(),
        );
    }
}
