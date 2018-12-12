<?php

declare(strict_types=1);

namespace KejawenLab\Application\SemartHris\Component\Tax\Repository;

use KejawenLab\Application\SemartHris\Component\Employee\Model\EmployeeInterface;
use KejawenLab\Application\SemartHris\Component\Salary\Model\PayrollPeriodInterface;
use KejawenLab\Application\SemartHris\Component\Tax\Model\TaxInterface;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
interface TaxRepositoryInterface
{
    /**
     * @param EmployeeInterface      $employee
     * @param PayrollPeriodInterface $period
     *
     * @return TaxInterface|null
     */
    public function findByEmployeeAndPeriod(EmployeeInterface $employee, PayrollPeriodInterface $period): ? TaxInterface;

    /**
     * @param EmployeeInterface      $employee
     * @param PayrollPeriodInterface $period
     *
     * @return TaxInterface
     */
    public function createTax(EmployeeInterface $employee, PayrollPeriodInterface $period): TaxInterface;

    /**
     * @param TaxInterface $tax
     */
    public function update(TaxInterface $tax): void;
}
