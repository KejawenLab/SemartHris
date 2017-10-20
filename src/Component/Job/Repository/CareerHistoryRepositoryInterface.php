<?php

namespace KejawenLab\Application\SemartHris\Component\Job\Repository;

use KejawenLab\Application\SemartHris\Component\Job\Model\CareerHistoryInterface;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@kejawenlab.com>
 */
interface CareerHistoryRepositoryInterface
{
    /**
     * @param CareerHistoryInterface $careerHistory
     */
    public function storeHistory(CareerHistoryInterface $careerHistory): void;
}
