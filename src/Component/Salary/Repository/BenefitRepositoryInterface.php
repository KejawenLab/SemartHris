<?php

namespace KejawenLab\Application\SemartHris\Component\Salary\Repository;

use KejawenLab\Application\SemartHris\Component\Employee\Model\EmployeeInterface;
use KejawenLab\Application\SemartHris\Component\Salary\Model\BenefitInterface;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@kejawenlab.com>
 */
interface BenefitRepositoryInterface
{
    /**
     * @param EmployeeInterface $employee
     *
     * @return BenefitInterface[]
     */
    public function findFixedByEmployee(EmployeeInterface $employee): array;
}
