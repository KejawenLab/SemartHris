<?php

declare(strict_types=1);

namespace KejawenLab\Application\SemartHris\Component\Salary\Repository;

use KejawenLab\Application\SemartHris\Component\Employee\Model\EmployeeInterface;
use KejawenLab\Application\SemartHris\Component\Salary\Model\CompanyPayrollCostInterface;
use KejawenLab\Application\SemartHris\Component\Salary\Model\ComponentInterface;
use KejawenLab\Application\SemartHris\Component\Salary\Model\PayrollDetailInterface;
use KejawenLab\Application\SemartHris\Component\Salary\Model\PayrollInterface;
use KejawenLab\Application\SemartHris\Component\Salary\Model\PayrollPeriodInterface;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
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
     * @return PayrollInterface|null
     */
    public function findPayroll(EmployeeInterface $employee, PayrollPeriodInterface $period): ? PayrollInterface;

    /**
     * @param EmployeeInterface      $employee
     * @param PayrollPeriodInterface $period
     *
     * @return PayrollInterface
     */
    public function createPayroll(EmployeeInterface $employee, PayrollPeriodInterface $period): PayrollInterface;

    /**
     * @param PayrollInterface   $payroll
     * @param ComponentInterface $component
     *
     * @return PayrollDetailInterface
     */
    public function createPayrollDetail(PayrollInterface $payroll, ComponentInterface $component): PayrollDetailInterface;

    /**
     * @param PayrollInterface   $payroll
     * @param ComponentInterface $component
     *
     * @return CompanyPayrollCostInterface
     */
    public function createCompanyCost(PayrollInterface $payroll, ComponentInterface $component): CompanyPayrollCostInterface;

    /**
     * @param PayrollInterface $payroll
     */
    public function store(PayrollInterface $payroll): void;

    /**
     * @param PayrollDetailInterface $payrollDetail
     */
    public function storeDetail(PayrollDetailInterface $payrollDetail): void;

    /**
     * @param CompanyPayrollCostInterface $companyCost
     */
    public function storeCompanyCost(CompanyPayrollCostInterface $companyCost): void;

    public function update(): void;
}
