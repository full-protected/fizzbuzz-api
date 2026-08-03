<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Statistic;
use App\Repository\StatisticRepository;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\ORM\EntityManagerInterface;

final class StatisticService
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly StatisticRepository $statisticRepository,
    ) {
    }

    public function registerRequest(int $int1, int $int2, int $requestLimit, string $str1, string $str2): void
    {
        $statistic = $this->statisticRepository->findOneByRequest($int1, $int2, $requestLimit, $str1, $str2);

        if (null !== $statistic) {
            $statistic->incrementHits();
            $this->entityManager->flush();

            return;
        }

        $statistic = new Statistic($int1, $int2, $requestLimit, $str1, $str2);
        $this->entityManager->persist($statistic);

        try {
            $this->entityManager->flush();
        } catch (UniqueConstraintViolationException) {
            // Une requête concurrente a créé la ligne entre notre lecture et notre écriture.
            // On détache l'entité en doublon et on incrémente la ligne existante à la place.
            $this->entityManager->detach($statistic);

            $existing = $this->statisticRepository->findOneByRequest($int1, $int2, $requestLimit, $str1, $str2);
            $existing?->incrementHits();
            $this->entityManager->flush();
        }
    }

    public function getMostRequested(): ?Statistic
    {
        return $this->statisticRepository->findMostRequested();
    }
}
