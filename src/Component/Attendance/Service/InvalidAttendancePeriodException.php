<?php

declare(strict_types=1);

namespace KejawenLab\Application\SemartHris\Component\Attendance\Service;

use Throwable;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class InvalidAttendancePeriodException extends \RuntimeException
{
    public function __construct(\DateTimeInterface $date, $code = 0, Throwable $previous = null)
    {
        parent::__construct(sprintf('Attendance with period %s month %s is not valid', $date->format('Y'), $date->format('m')), $code, $previous);
    }
}
