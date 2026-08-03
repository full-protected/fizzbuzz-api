<?php

declare(strict_types=1);

namespace App\ApiResource;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use App\State\StatisticsProvider;

#[ApiResource(
    formats: ['json'],
    operations: [
        new Get(
            uriTemplate: '/statistics',
            provider: StatisticsProvider::class,
        ),
    ],
)]
final class Statistics
{
    public function __construct(
        public int $int1,
        public int $int2,
        public int $limit,
        public string $str1,
        public string $str2,
        public int $hits,
    ) {
    }
}
