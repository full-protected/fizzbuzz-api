<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Statistic;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Statistic>
 */
final class StatisticRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Statistic::class);
    }

    public function findOneByRequest(int $int1, int $int2, int $requestLimit, string $str1, string $str2): ?Statistic
    {
        return $this->findOneBy([
            'int1' => $int1,
            'int2' => $int2,
            'requestLimit' => $requestLimit,
            'str1' => $str1,
            'str2' => $str2,
        ]);
    }

    public function findMostRequested(): ?Statistic
    {
        return $this->createQueryBuilder('s')
            ->orderBy('s.hits', 'DESC')
            ->addOrderBy('s.id', 'ASC')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }
}
