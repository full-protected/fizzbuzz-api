<?php

declare(strict_types=1);

namespace App\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\ApiResource\Statistics;
use App\Service\StatisticService;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @implements ProviderInterface<Statistics>
 */
final class StatisticsProvider implements ProviderInterface
{
    public function __construct(
        private readonly StatisticService $statisticService,
    ) {
    }

    public function provide(Operation $operation, array $uriVariables = [], array $context = []): Statistics
    {
        $statistic = $this->statisticService->getMostRequested();

        if (null === $statistic) {
            throw new NotFoundHttpException('No FizzBuzz request has been made yet.');
        }

        return new Statistics(
            int1: $statistic->getInt1(),
            int2: $statistic->getInt2(),
            limit: $statistic->getRequestLimit(),
            str1: $statistic->getStr1(),
            str2: $statistic->getStr2(),
            hits: $statistic->getHits(),
        );
    }
}
