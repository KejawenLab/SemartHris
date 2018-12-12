<?php

declare(strict_types=1);

namespace KejawenLab\Application\SemartHris\Repository;

use KejawenLab\Application\SemartHris\Component\Employee\Model\EmployeeInterface;
use KejawenLab\Application\SemartHris\Component\Salary\Model\PayrollPeriodInterface;
use KejawenLab\Application\SemartHris\Component\Salary\Repository\PayrollPeriodRepositoryInterface;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
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
        $entity->setYear((int) $date->format('Y'));
        $entity->setMonth((int) $date->format('n'));
        $entity->setCompany($employee->getCompany());
        $entity->setClosed(false);

        $this->update($entity);

        return $entity;
    }

    /**
     * @param PayrollPeriodInterface $payrollPeriod
     */
    public function closeExcept(PayrollPeriodInterface $payrollPeriod): void
    {
        $queryBuilder = $this->entityManager->createQueryBuilder();
        $queryBuilder->from($this->entityClass, 'o');
        $queryBuilder->update();
        $queryBuilder->set('o.closed', $queryBuilder->expr()->literal(true));
        $queryBuilder->andWhere($queryBuilder->expr()->neq('o.id', $queryBuilder->expr()->literal($payrollPeriod->getId())));

        $queryBuilder->getQuery()->execute();
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
     * @param \DateTimeInterface $date
     *
     * @return bool
     */
    public function isEmptyOrNotEqueal(\DateTimeInterface $date): bool
    {
        $queryBuilder = $this->entityManager->createQueryBuilder();
        $queryBuilder->from($this->entityClass, 'p');
        $queryBuilder->select('COUNT(1)');
        $queryBuilder->andWhere($queryBuilder->expr()->neq('p.year', $queryBuilder->expr()->literal($date->format('Y'))));
        $queryBuilder->andWhere($queryBuilder->expr()->neq('p.month', $queryBuilder->expr()->literal($date->format('n'))));

        $result = $queryBuilder->getQuery()->getSingleScalarResult();
        if (0 === $result) {
            return true;
        }

        return false;
    }

    /**
     * @return bool
     */
    public function hasUnclosedPeriod(): bool
    {
        $queryBuilder = $this->entityManager->createQueryBuilder();
        $queryBuilder->from($this->entityClass, 'p');
        $queryBuilder->select('COUNT(1)');
        $queryBuilder->andWhere($queryBuilder->expr()->eq('p.closed', $queryBuilder->expr()->literal(false)));

        $result = $queryBuilder->getQuery()->getSingleScalarResult();
        if (0 === $result) {
            return false;
        }

        return true;
    }
}
