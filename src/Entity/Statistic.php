<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\StatisticRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: StatisticRepository::class)]
#[ORM\Table(name: 'statistic')]
#[ORM\UniqueConstraint(
    name: 'unique_request',
    columns: ['int1', 'int2', 'request_limit', 'str1', 'str2'],
)]
class Statistic
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private int $int1;

    #[ORM\Column]
    private int $int2;

    #[ORM\Column(name: 'request_limit')]
    private int $requestLimit;

    #[ORM\Column(length: 100)]
    private string $str1;

    #[ORM\Column(length: 100)]
    private string $str2;

    #[ORM\Column]
    private int $hits = 0;

    public function __construct(int $int1, int $int2, int $requestLimit, string $str1, string $str2)
    {
        $this->int1 = $int1;
        $this->int2 = $int2;
        $this->requestLimit = $requestLimit;
        $this->str1 = $str1;
        $this->str2 = $str2;
        $this->hits = 1;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getInt1(): int
    {
        return $this->int1;
    }

    public function getInt2(): int
    {
        return $this->int2;
    }

    public function getRequestLimit(): int
    {
        return $this->requestLimit;
    }

    public function getStr1(): string
    {
        return $this->str1;
    }

    public function getStr2(): string
    {
        return $this->str2;
    }

    public function getHits(): int
    {
        return $this->hits;
    }

    public function incrementHits(): void
    {
        ++$this->hits;
    }
}
