<?php

namespace KejawenLab\Application\SemartHris\Repository;

use KejawenLab\Application\SemartHris\Component\Employee\Model\EmployeeInterface;
use KejawenLab\Application\SemartHris\Component\Salary\Model\PayrollPeriodInterface;
use KejawenLab\Application\SemartHris\Component\Salary\Repository\PayrollPeriodRepositoryInterface;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@kejawenlab.com>
 */
class PayrollPeriodRepository extends Repository implements PayrollPeriodRepositoryInterface
{
    /**
     * @param EmployeeInterface  $employee
     * @param \DateTimeInterface $date
     *
     * @return PayrollPeriodInterface|null
     */
    public function findByEmployeeAndDate(EmployeeInterface $employee, \DateTimeInterface $date): ? PayrollPeriodInterface
    {
        return $this->entityManager->getRepository($this->entityClass)->findOneBy([
            'company' => $employee->getCompany(),
            'year' => $date->format('Y'),
            'month' => $date->format('n'),
        ]);
    }

    /**
     * @param EmployeeInterface  $employee
     * @param \DateTimeInterface $date
     *
     * @return PayrollPeriodInterface
     */
    public function createNew(EmployeeInterface $employee, \DateTimeInterface $date): PayrollPeriodInterface
    {
        /** @var PayrollPeriodInterface $entity */
        $entity = new $this->entityClass();
        $entity->setYear($date->format('Y'));
        $entity->setMonth($date->format('n'));
        $entity->setCompany($employee->getCompany());
        $entity->setClosed(false);

        $this->update($entity);

        return $entity;
    }

    /**
     * @param PayrollPeriodInterface $payrollPeriod
     */
    public function update(PayrollPeriodInterface $payrollPeriod): void
    {
        $manager = $this->entityManager;
        $manager->persist($payrollPeriod);
        $manager->flush();
    }

    /**
     * @return bool
     */
    public function isEmpty(): bool
    {
        $queryBuilder = $this->entityManager->createQueryBuilder();
        $queryBuilder->from($this->entityClass, 'p');
        $queryBuilder->select('COUNT(1)');

        $result = $queryBuilder->getQuery()->getSingleScalarResult();
        if (0 === $result) {
            return true;
        }

        return false;
    }
}
