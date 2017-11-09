<?php

namespace KejawenLab\Application\SemartHris\Component\Salary\Service;

use KejawenLab\Application\SemartHris\Component\Employee\Model\EmployeeInterface;
use KejawenLab\Application\SemartHris\Component\Salary\Processor\ProcessorInterface;
use KejawenLab\Application\SemartHris\Component\Salary\Repository\PayrollPeriodRepositoryInterface;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@kejawenlab.com>
 */
class PayrollProcessor
{
    /**
     * @var ProcessorInterface
     */
    private $payrollProcessor;

    /**
     * @var PayrollPeriodRepositoryInterface
     */
    private $payrollPeriodRepository;

    /**
     * @param ProcessorInterface               $processor
     * @param PayrollPeriodRepositoryInterface $repository
     */
    public function __construct(ProcessorInterface $processor, PayrollPeriodRepositoryInterface $repository)
    {
        $this->payrollProcessor = $processor;
        $this->payrollPeriodRepository = $repository;
    }

    /**
     * @param EmployeeInterface  $employee
     * @param \DateTimeInterface $date
     */
    public function process(EmployeeInterface $employee, \DateTimeInterface $date): void
    {
        $this->validate($employee, $date);
        $this->payrollProcessor->process($employee, $date);
    }

    /**
     * @param EmployeeInterface  $employee
     * @param \DateTimeInterface $date
     */
    private function validate(EmployeeInterface $employee, \DateTimeInterface $date): void
    {
        /** @var \DateTime $prevPeriod */
        $prevPeriod = clone $date;
        $prevPeriod->sub(new \DateInterval('P1M'));

        $prev = $this->payrollPeriodRepository->findByEmployeeAndDate($employee, $prevPeriod);
        if (!$prev && $this->payrollPeriodRepository->isEmpty()) {
            throw new \InvalidArgumentException(sprintf('Can\'t process invalid payroll period.'));
        }

        $period = $this->payrollPeriodRepository->findByEmployeeAndDate($employee, $date);
        if (!$period) {
            $period = $this->payrollPeriodRepository->createNew($employee, $date);
        }

        if ($period->isClosed()) {
            throw new \InvalidArgumentException(sprintf('Payroll is closed.'));
        }

        if ($prev) {
            $prev->setClosed(true);
            $this->payrollPeriodRepository->update($prev);
        }
    }
}
