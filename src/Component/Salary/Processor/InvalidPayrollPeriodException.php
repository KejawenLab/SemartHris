<?php

declare(strict_types=1);

namespace KejawenLab\Application\SemartHris\Component\Salary\Processor;

use Throwable;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class InvalidPayrollPeriodException extends \RuntimeException
{
    public function __construct(\DateTimeInterface $date, $code = 0, Throwable $previous = null)
    {
        parent::__construct(sprintf('Payroll with period %s month %s is not valid or be closed', $date->format('Y'), $date->format('m')), $code, $previous);
    }
}
