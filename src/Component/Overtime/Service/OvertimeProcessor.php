<?php

declare(strict_types=1);

namespace KejawenLab\Application\SemartHris\Component\Overtime\Service;

use KejawenLab\Application\SemartHris\Component\Employee\Model\EmployeeInterface;
use KejawenLab\Application\SemartHris\Component\Overtime\Repository\OvertimeRepositoryInterface;
use KejawenLab\Application\SemartHris\Kernel;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class OvertimeProcessor
{
    const CUT_OFF_LAST_DATE = -1;

    /**
     * @var OvertimeRepositoryInterface
     */
    private $overtimeRepository;

    /**
     * @var int
     */
    private $cutOffDate;

    /**
     * @param OvertimeRepositoryInterface $repository
     * @param int                         $cutOffDate
     */
    public function __construct(OvertimeRepositoryInterface $repository, int $cutOffDate)
    {
        $this->overtimeRepository = $repository;
        $this->cutOffDate = $cutOffDate;
    }

    /**
     * @param EmployeeInterface  $employee
     * @param \DateTimeInterface $date
     */
    public function process(EmployeeInterface $employee, \DateTimeInterface $date): void
    {
        if (self::CUT_OFF_LAST_DATE === $this->cutOffDate) {
            $this->processFullMonth($employee, $date);
        } else {
            $this->processPartialMonth($employee, $date, $this->cutOffDate);
        }
    }

    /**
     * @param EmployeeInterface  $employee
     * @param \DateTimeInterface $date
     */
    private function processFullMonth(EmployeeInterface $employee, \DateTimeInterface $date): void
    {
        $count = $date->format('t');
        for ($i = 1; $i <= $count; ++$i) {
            $attendanceDate = \DateTime::createFromFormat('Y-m-j', sprintf('%s-%d', $date->format('Y-m'), $i));
            $this->doProcess($employee, $attendanceDate);
        }
    }

    /**
     * @param EmployeeInterface  $employee
     * @param \DateTimeInterface $date
     * @param int                $cutOff
     */
    private function processPartialMonth(EmployeeInterface $employee, \DateTimeInterface $date, int $cutOff): void
    {
        /** @var \DateTime $date */
        $countPrevMonth = $date->sub(new \DateInterval('P1M'))->format('t');
        for ($i = ($cutOff + 1); $i <= $countPrevMonth; ++$i) {
            $attendanceDate = \DateTime::createFromFormat('Y-m-j', sprintf('%s-%d', $date->format('Y-m'), $i));
            $this->doProcess($employee, $attendanceDate);
        }

        for ($i = 1; $i <= $cutOff; ++$i) {
            $attendanceDate = \DateTime::createFromFormat('Y-m-j', sprintf('%s-%d', $date->format('Y-m'), $i));
            $this->doProcess($employee, $attendanceDate);
        }
    }

    /**
     * @param EmployeeInterface  $employee
     * @param \DateTimeInterface $date
     */
    private function doProcess(EmployeeInterface $employee, \DateTimeInterface $date): void
    {
        $overtime = $this->overtimeRepository->findByEmployeeAndDate($employee, $date);
        if (!$overtime) {
            return;
        }

        $overtime->setDescription(sprintf('%s#%s', Kernel::SEMART_VERSION, $overtime->getDescription()));
        $this->overtimeRepository->update($overtime);
    }
}
