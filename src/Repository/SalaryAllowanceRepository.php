<?php

namespace KejawenLab\Application\SemartHris\Repository;

use KejawenLab\Application\SemartHris\Component\Employee\Model\EmployeeInterface;
use KejawenLab\Application\SemartHris\Component\Salary\Model\AllowanceInterface;
use KejawenLab\Application\SemartHris\Component\Salary\Repository\AllowanceRepositoryInterface;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@kejawenlab.com>
 */
class SalaryAllowanceRepository extends Repository implements AllowanceRepositoryInterface
{
    /**
     * @param EmployeeInterface  $employee
     * @param \DateTimeInterface $date
     *
     * @return AllowanceInterface|null
     */
    public function findByEmployeeAndDate(EmployeeInterface $employee, \DateTimeInterface $date): ? AllowanceInterface
    {
        return $this->entityManager->getRepository($this->entityClass)->findOneBy([
            'employee' => $employee,
            'year' => $date->format('Y'),
            'month' => $date->format('n'),
        ]);
    }

    /**
     * @param EmployeeInterface  $employee
     * @param \DateTimeInterface $date
     *
     * @return AllowanceInterface
     */
    public function createNew(EmployeeInterface $employee, \DateTimeInterface $date): AllowanceInterface
    {
        /** @var AllowanceInterface $entity */
        $entity = new $this->entityClass();
        $entity->setYear($date->format('Y'));
        $entity->setMonth($date->format('n'));
        $entity->setEmployee($employee->getCompany());

        $this->update($entity);

        return $entity;
    }

    /**
     * @param AllowanceInterface $allowance
     */
    public function update(AllowanceInterface $allowance): void
    {
        $manager = $this->entityManager;
        $manager->persist($allowance);
        $manager->flush();
    }
}
