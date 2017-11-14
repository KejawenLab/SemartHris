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
    public function findFixedByEmployee(EmployeeInterface $employee): array
    {
        $queryBuilder = $this->entityManager->createQueryBuilder();
        $queryBuilder->from($this->entityClass, 'b');
        $queryBuilder->select('b');
        $queryBuilder->innerJoin('b.component', 'c');
        $queryBuilder->andWhere($queryBuilder->expr()->eq('c.fixed', $queryBuilder->expr()->literal(true)));

        return $queryBuilder->getQuery()->getResult();
    }
}
