<?php

declare(strict_types=1);

namespace KejawenLab\Application\SemartHris\Component\Salary\Repository;

use KejawenLab\Application\SemartHris\Component\Employee\Model\EmployeeInterface;
use KejawenLab\Application\SemartHris\Component\Salary\Model\PayrollPeriodInterface;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
interface PayrollPeriodRepositoryInterface
{
    /**
     * @param EmployeeInterface  $employee
     * @param \DateTimeInterface $date
     *
     * @return PayrollPeriodInterface|null
     */
    public function findByEmployeeAndDate(EmployeeInterface $employee, \DateTimeInterface $date): ? PayrollPeriodInterface;

    /**
     * @param EmployeeInterface  $employee
     * @param \DateTimeInterface $date
     *
     * @return PayrollPeriodInterface
     */
    public function createNew(EmployeeInterface $employee, \DateTimeInterface $date): PayrollPeriodInterface;

    /**
     * @param PayrollPeriodInterface $payrollPeriod
     */
    public function closeExcept(PayrollPeriodInterface $payrollPeriod): void;

    /**
     * @param PayrollPeriodInterface $payrollPeriod
     */
    public function update(PayrollPeriodInterface $payrollPeriod): void;

    /**
     * @param \DateTimeInterface $date
     *
     * @return bool
     */
    public function isEmptyOrNotEqueal(\DateTimeInterface $date): bool;

    /**
     * @return bool
     */
    public function hasUnclosedPeriod(): bool;
}
