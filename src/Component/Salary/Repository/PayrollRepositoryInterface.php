<?php

namespace KejawenLab\Application\SemartHris\Component\Salary\Repository;

use KejawenLab\Application\SemartHris\Component\Employee\Model\EmployeeInterface;
use KejawenLab\Application\SemartHris\Component\Salary\Model\PayrollDetailInterface;
use KejawenLab\Application\SemartHris\Component\Salary\Model\PayrollInterface;
use KejawenLab\Application\SemartHris\Component\Salary\Model\PayrollPeriodInterface;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@kejawenlab.com>
 */
interface PayrollRepositoryInterface
{
    /**
     * @param EmployeeInterface $employee
     *
     * @return bool
     */
    public function hasPayroll(EmployeeInterface $employee): bool;

    /**
     * @param EmployeeInterface      $employee
     * @param PayrollPeriodInterface $period
     *
     * @return PayrollInterface
     */
    public function createPayroll(EmployeeInterface $employee, PayrollPeriodInterface $period): PayrollInterface;

    /**
     * @param PayrollInterface $payroll
     *
     * @return PayrollDetailInterface
     */
    public function createPayrollDetail(PayrollInterface $payroll): PayrollDetailInterface;

    /**
     * @param PayrollInterface $payroll
     */
    public function store(PayrollInterface $payroll): void;

    /**
     * @param PayrollDetailInterface $payrollDetail
     */
    public function storeDetail(PayrollDetailInterface $payrollDetail): void;

    public function update(): void;
}
