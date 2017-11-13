<?php

namespace KejawenLab\Application\SemartHris\Repository;

use KejawenLab\Application\SemartHris\Component\Employee\Model\EmployeeInterface;
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
    public function createNew(EmployeeInterface $employee, PayrollPeriodInterface $period): PayrollInterface
    {
        /** @var PayrollInterface $payroll */
        $payroll = new $this->entityClass();
        $payroll->setEmployee($employee);
        $payroll->setPeriod($period);

        return $payroll;
    }

    /**
     * @param PayrollInterface $payroll
     *
     * @return PayrollDetailInterface
     */
    public function createDetail(PayrollInterface $payroll): PayrollDetailInterface
    {
        /** @var PayrollDetailInterface $payrollDetail */
        $payrollDetail = new $this->detailClass();
        $payrollDetail->setPayroll($payroll);

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
}
