<?php

declare(strict_types=1);

namespace KejawenLab\Application\SemartHris\Component\Tax\Processor;

use KejawenLab\Application\SemartHris\Component\Employee\Model\EmployeeInterface;
use KejawenLab\Application\SemartHris\Component\Salary\Model\PayrollPeriodInterface;
use KejawenLab\Application\SemartHris\Component\Salary\Repository\PayrollRepositoryInterface;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@kejawenlab.com>
 */
class TaxProcessor extends Processor
{
    /**
     * @var TaxProcessorInterface[]
     */
    private $processors;

    /**
     * @param PayrollRepositoryInterface $payrollRepository
     * @param array                      $processors
     */
    public function __construct(PayrollRepositoryInterface $payrollRepository, array $processors = [])
    {
        $this->setPayrollRepository($payrollRepository);

        $this->processors = [];
        foreach ($processors as $processor) {
            $this->addProcessor($processor);
        }
    }

    /**
     * @param EmployeeInterface      $employee
     * @param PayrollPeriodInterface $period
     *
     * @return float
     */
    public function process(EmployeeInterface $employee, PayrollPeriodInterface $period): float
    {
        foreach ($this->processors as $processor) {
            try {
                if ($processor instanceof Processor) {
                    $processor->setPayrollRepository($this->payrollRepository);
                }

                return $processor->process($employee, $period);
            } catch (TaxProcessorException $exception) {
                continue;
            }
        }

        throw new TaxProcessorException();
    }

    /**
     * @param TaxProcessorInterface $processor
     */
    private function addProcessor(TaxProcessorInterface $processor): void
    {
        $this->processors[] = $processor;
    }
}
