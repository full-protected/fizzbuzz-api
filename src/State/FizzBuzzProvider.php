<?php

declare(strict_types=1);

namespace App\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\ApiResource\FizzBuzz;
use App\Service\FizzBuzzService;
use App\Service\StatisticService;

/**
 * @implements ProviderInterface<FizzBuzz>
 */
final class FizzBuzzProvider implements ProviderInterface
{
    public function __construct(
        private readonly FizzBuzzService $fizzBuzzService,
        private readonly StatisticService $statisticService,
    ) {
    }

    public function provide(Operation $operation, array $uriVariables = [], array $context = []): FizzBuzz
    {
        $request = $context['request'];

        $int1 = (int) $request->query->get('int1');
        $int2 = (int) $request->query->get('int2');
        $limit = (int) $request->query->get('limit');
        $str1 = (string) $request->query->get('str1');
        $str2 = (string) $request->query->get('str2');

        $result = $this->fizzBuzzService->generate($int1, $int2, $limit, $str1, $str2);

        $this->statisticService->registerRequest($int1, $int2, $limit, $str1, $str2);

        return new FizzBuzz(result: $result);
    }
}
