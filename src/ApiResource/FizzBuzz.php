<?php

declare(strict_types=1);

namespace App\ApiResource;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\QueryParameter;
use App\State\FizzBuzzProvider;
use Symfony\Component\Validator\Constraints as Assert;

#[ApiResource(
    formats: ['json'],
    operations: [
        new Get(
            uriTemplate: '/fizzbuzz',
            provider: FizzBuzzProvider::class,
            parameters: [
                'int1' => new QueryParameter(
                    required: true,
                    schema: ['type' => 'integer'],
                    constraints: [new Assert\NotBlank(), new Assert\Positive()],
                    description: 'Positive integer used as the first divisor.',
                ),
                'int2' => new QueryParameter(
                    required: true,
                    schema: ['type' => 'integer'],
                    constraints: [new Assert\NotBlank(), new Assert\Positive()],
                    description: 'Positive integer used as the second divisor.',
                ),
                'limit' => new QueryParameter(
                    required: true,
                    schema: ['type' => 'integer'],
                    constraints: [new Assert\NotBlank(), new Assert\Positive(), new Assert\LessThanOrEqual(100000)],
                    description: 'Maximum number included in the generated sequence.',
                ),
                'str1' => new QueryParameter(
                    required: true,
                    schema: ['type' => 'string'],
                    constraints: [new Assert\NotBlank(), new Assert\Length(max: 100)],
                    description: 'String replacing numbers divisible by int1.',
                ),
                'str2' => new QueryParameter(
                    required: true,
                    schema: ['type' => 'string'],
                    constraints: [new Assert\NotBlank(), new Assert\Length(max: 100)],
                    description: 'String replacing numbers divisible by int2.',
                ),
            ],
        ),
    ],
)]
final class FizzBuzz
{
    /**
     * @param list<string> $result
     */
    public function __construct(
        public array $result = [],
    ) {
    }
}
