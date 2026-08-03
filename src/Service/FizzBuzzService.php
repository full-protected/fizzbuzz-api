<?php

declare(strict_types=1);

namespace App\Service;

final class FizzBuzzService
{
    /**
     * @return list<string>
     */
    public function generate(int $int1, int $int2, int $limit, string $str1, string $str2): array
    {
        $result = [];

        for ($number = 1; $number <= $limit; ++$number) {
            $result[] = $this->resolve($number, $int1, $int2, $str1, $str2);
        }

        return $result;
    }

    private function resolve(int $number, int $int1, int $int2, string $str1, string $str2): string
    {
        $isMultipleOfInt1 = 0 === $number % $int1;
        $isMultipleOfInt2 = 0 === $number % $int2;

        if ($isMultipleOfInt1 && $isMultipleOfInt2) {
            return $str1.$str2;
        }

        if ($isMultipleOfInt1) {
            return $str1;
        }

        if ($isMultipleOfInt2) {
            return $str2;
        }

        return (string) $number;
    }
}
