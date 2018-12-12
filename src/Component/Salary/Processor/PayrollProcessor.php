<?php

declare(strict_types=1);

namespace KejawenLab\Application\SemartHris\Component\Salary\Processor;

use KejawenLab\Application\SemartHris\Component\Employee\Model\EmployeeInterface;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class PayrollProcessor implements PayrollProcessorInterface
{
    const SEMARTHRIS_PAYROLL_PROCESSOR = 'semarthris.payroll_processor';

    /**
     * @var PayrollProcessorInterface[]
     */
    private $processors;

    /**
     * @param array $processors
     */
    public function __construct(array $processors = [])
    {
        $this->processors = [];
        foreach ($processors as $processor) {
            $this->addProcessor($processor);
        }
    }

    /**
     * @param EmployeeInterface  $employee
     * @param \DateTimeInterface $date
     */
    public function process(EmployeeInterface $employee, \DateTimeInterface $date): void
    {
        foreach ($this->processors as $processor) {
            $processor->process($employee, $date);
        }
    }

    /**
     * @param PayrollProcessorInterface $processor
     */
    private function addProcessor(PayrollProcessorInterface $processor): void
    {
        $this->processors[] = $processor;
    }
}
