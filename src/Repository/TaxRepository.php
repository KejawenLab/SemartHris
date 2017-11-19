<?php

declare(strict_types=1);

namespace KejawenLab\Application\SemartHris\Repository;

use KejawenLab\Application\SemartHris\Component\Employee\Model\EmployeeInterface;
use KejawenLab\Application\SemartHris\Component\Salary\Model\PayrollPeriodInterface;
use KejawenLab\Application\SemartHris\Component\Tax\Model\TaxInterface;
use KejawenLab\Application\SemartHris\Component\Tax\Repository\TaxRepositoryInterface;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@kejawenlab.com>
 */
class TaxRepository extends Repository implements TaxRepositoryInterface
{
    /**
     * @param EmployeeInterface      $employee
     * @param PayrollPeriodInterface $period
     *
     * @return TaxInterface|null
     */
    public function findByEmployeeAndPeriod(EmployeeInterface $employee, PayrollPeriodInterface $period): ? TaxInterface
    {
        return $this->entityManager->getRepository($this->entityClass)->findOneBy([
            'employee' => $employee,
            'period' => $period,
        ]);
    }

    /**
     * @param EmployeeInterface      $employee
     * @param PayrollPeriodInterface $period
     *
     * @return TaxInterface
     */
    public function createTax(EmployeeInterface $employee, PayrollPeriodInterface $period): TaxInterface
    {
        $tax = $this->findByEmployeeAndPeriod($employee, $period);
        if (!$tax) {
            /** @var TaxInterface $tax */
            $tax = new $this->entityClass();
            $tax->setEmployee($employee);
            $tax->setPeriod($period);
            $tax->setTaxGroup($employee->getTaxGroup());
        }

        return $tax;
    }

    /**
     * @param TaxInterface $tax
     */
    public function update(TaxInterface $tax): void
    {
        $this->entityManager->persist($tax);
        $this->entityManager->flush();
    }
}
