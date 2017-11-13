<?php

namespace KejawenLab\Application\SemartHris\Repository;

use KejawenLab\Application\SemartHris\Component\Employee\Model\EmployeeInterface;
use KejawenLab\Application\SemartHris\Component\Salary\Model\BenefitInterface;
use KejawenLab\Application\SemartHris\Component\Salary\Repository\BenefitRepositoryInterface;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@kejawenlab.com>
 */
class SalaryBenefitRepository extends Repository implements BenefitRepositoryInterface
{
    /**
     * @param EmployeeInterface $employee
     *
     * @return BenefitInterface[]
     */
    public function findByEmployee(EmployeeInterface $employee): array
    {
        return $this->entityManager->getRepository($this->entityClass)->findBy(['employee' => $employee]);
    }
}
