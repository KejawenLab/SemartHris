<?php

declare(strict_types=1);

namespace KejawenLab\Application\SemartHris\Repository;

use KejawenLab\Application\SemartHris\Component\Contract\Repository\ContractableRepositoryInterface;
use KejawenLab\Application\SemartHris\Component\Job\Model\CareerHistoryInterface;
use KejawenLab\Application\SemartHris\Component\Job\Repository\CareerHistoryRepositoryInterface;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class CareerHistoryRepository extends Repository implements CareerHistoryRepositoryInterface, ContractableRepositoryInterface
{
    use ContractableRepositoryTrait;

    /**
     * @param CareerHistoryInterface $careerHistory
     */
    public function storeHistory(CareerHistoryInterface $careerHistory): void
    {
        $this->entityManager->persist($careerHistory);
        $this->entityManager->flush();
    }
}
