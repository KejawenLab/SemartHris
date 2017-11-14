<?php

namespace KejawenLab\Application\SemartHris\Repository;

use KejawenLab\Application\SemartHris\Component\Employee\Model\EmployeeInterface;
use KejawenLab\Application\SemartHris\Component\Salary\Model\ComponentInterface;
use KejawenLab\Application\SemartHris\Component\Salary\Model\PayrollDetailInterface;
use KejawenLab\Application\SemartHris\Component\Salary\Model\PayrollInterface;
use KejawenLab\Application\SemartHris\Component\Salary\Model\PayrollPeriodInterface;
use KejawenLab\Application\SemartHris\Component\Salary\Repository\PayrollRepositoryInterface;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@kejawenlab.com>
 */
class PayrollRepository extends Repository implements PayrollRepositoryInterface
{
    /**
     * @var string
     */
    private $detailClass;

    /**
     * @param string $detailClass
     */
    public function __construct(string $detailClass)
    {
        $this->detailClass = $detailClass;
    }

    /**
     * @param EmployeeInterface $employee
     *
     * @return bool
     */
    public function hasPayroll(EmployeeInterface $employee): bool
    {
        $payroll = $this->entityManager->getRepository($this->entityClass)->findOneBy(['employee' => $employee]);
        if ($payroll) {
            return true;
        }

        return false;
    }

    /**
     * @param EmployeeInterface      $employee
     * @param PayrollPeriodInterface $period
     *
     * @return PayrollInterface
     */
    public function createPayroll(EmployeeInterface $employee, PayrollPeriodInterface $period): PayrollInterface
    {
        $payroll = $this->findPayroll($employee, $period);
        if (!$payroll) {
            /** @var PayrollInterface $payroll */
            $payroll = new $this->entityClass();
            $payroll->setEmployee($employee);
            $payroll->setPeriod($period);
        }

        return $payroll;
    }

    /**
     * @param PayrollInterface   $payroll
     * @param ComponentInterface $component
     *
     * @return PayrollDetailInterface
     */
    public function createPayrollDetail(PayrollInterface $payroll, ComponentInterface $component): PayrollDetailInterface
    {
        $payrollDetail = $this->findPayrollDetail($payroll, $component);
        if (!$payrollDetail) {
            /** @var PayrollDetailInterface $payrollDetail */
            $payrollDetail = new $this->detailClass();
            $payrollDetail->setPayroll($payroll);
            $payrollDetail->setComponent($component);
        }

        return $payrollDetail;
    }

    /**
     * @param PayrollInterface $payroll
     */
    public function store(PayrollInterface $payroll): void
    {
        $this->entityManager->persist($payroll);
    }

    /**
     * @param PayrollDetailInterface $payrollDetail
     */
    public function storeDetail(PayrollDetailInterface $payrollDetail): void
    {
        $this->entityManager->persist($payrollDetail);
    }

    public function update(): void
    {
        $this->entityManager->flush();
    }

    /**
     * @param EmployeeInterface      $employee
     * @param PayrollPeriodInterface $period
     *
     * @return PayrollInterface|null
     */
    private function findPayroll(EmployeeInterface $employee, PayrollPeriodInterface $period): ? PayrollInterface
    {
        return $this->entityManager->getRepository($this->entityClass)->findOneBy([
            'employee' => $employee,
            'period' => $period,
        ]);
    }

    /**
     * @param PayrollInterface   $payroll
     * @param ComponentInterface $component
     *
     * @return PayrollDetailInterface|null
     */
    private function findPayrollDetail(PayrollInterface $payroll, ComponentInterface $component): ? PayrollDetailInterface
    {
        return $this->entityManager->getRepository($this->detailClass)->findOneBy([
            'payroll' => $payroll,
            'component' => $component,
        ]);
    }
}
