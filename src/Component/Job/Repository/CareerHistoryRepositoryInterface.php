<?php

declare(strict_types=1);

namespace KejawenLab\Application\SemartHris\Component\Job\Repository;

use KejawenLab\Application\SemartHris\Component\Job\Model\CareerHistoryInterface;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
interface CareerHistoryRepositoryInterface
{
    /**
     * @param CareerHistoryInterface $careerHistory
     */
    public function storeHistory(CareerHistoryInterface $careerHistory): void;
}
