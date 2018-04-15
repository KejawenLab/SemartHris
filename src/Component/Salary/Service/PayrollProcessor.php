<?php

declare(strict_types=1);

namespace KejawenLab\Application\SemartHris\Component\Salary\Service;

use KejawenLab\Application\SemartHris\Component\Employee\Model\EmployeeInterface;
use KejawenLab\Application\SemartHris\Component\Salary\Processor\InvalidPayrollPeriodException;
use KejawenLab\Application\SemartHris\Component\Salary\Processor\PayrollProcessorInterface;
use KejawenLab\Application\SemartHris\Component\Salary\Repository\PayrollPeriodRepositoryInterface;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class PayrollProcessor
{
    /**
     * @var PayrollProcessorInterface
     */
    private $payrollProcessor;

    /**
     * @var PayrollPeriodRepositoryInterface
     */
    private $payrollPeriodRepository;

    /**
     * @param PayrollProcessorInterface        $processor
     * @param PayrollPeriodRepositoryInterface $repository
     */
    public function __construct(PayrollProcessorInterface $processor, PayrollPeriodRepositoryInterface $repository)
    {
        $this->payrollProcessor = $processor;
        $this->payrollPeriodRepository = $repository;
    }

    /**
     * @param EmployeeInterface $employee
     * @param \DateTimeInterface $date
     *
     * @throws \Exception
     */
    public function process(EmployeeInterface $employee, \DateTimeInterface $date): void
    {
        $this->validate($employee, $date);
        $this->payrollProcessor->process($employee, $date);
    }

    /**
     * @param EmployeeInterface $employee
     * @param \DateTimeInterface $date
     *
     * @throws \Exception
     */
    private function validate(EmployeeInterface $employee, \DateTimeInterface $date): void
    {
        /** @var \DateTime $prevPeriod */
        $prevPeriod = clone $date;
        $prevPeriod->sub(new \DateInterval('P1M'));

        $prev = $this->payrollPeriodRepository->findByEmployeeAndDate($employee, $prevPeriod);
        if (!$prev && !$this->payrollPeriodRepository->isEmptyOrNotEqueal($date)) {
            throw new \InvalidArgumentException(sprintf('Previous period must be processed before processing it period.'));
        }

        $period = $this->payrollPeriodRepository->findByEmployeeAndDate($employee, $date);
        if (!$period) {
            $period = $this->payrollPeriodRepository->createNew($employee, $date);
        }

        if ($prev) {
            if ($prev->getMonth() !== $period->getMonth() - 1) {
                throw new \InvalidArgumentException(sprintf('Previous period must be processed before processing it period.'));
            }
        }

        if ($period->isClosed()) {
            throw new InvalidPayrollPeriodException($date);
        }

        $this->payrollPeriodRepository->closeExcept($period);
    }
}
