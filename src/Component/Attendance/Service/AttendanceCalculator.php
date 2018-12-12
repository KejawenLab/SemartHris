<?php

declare(strict_types=1);

namespace KejawenLab\Application\SemartHris\Component\Attendance\Service;

use KejawenLab\Application\SemartHris\Component\Attendance\Model\AttendanceInterface;
use KejawenLab\Application\SemartHris\Component\Attendance\Model\ShiftmentInterface;
use KejawenLab\Application\SemartHris\Component\Attendance\Repository\WorkshiftRepositoryInterface;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class AttendanceCalculator
{
    /**
     * @var WorkshiftRepositoryInterface
     */
    private $workshiftRepository;

    /**
     * @param WorkshiftRepositoryInterface $repository
     */
    public function __construct(WorkshiftRepositoryInterface $repository)
    {
        $this->workshiftRepository = $repository;
    }

    /**
     * @param AttendanceInterface $attendance
     */
    public function calculate(AttendanceInterface $attendance): void
    {
        if (-1 === $attendance->getLateIn()) {
            $attendance->setLateIn(0);
        }

        $workshift = $this->workshiftRepository->findByEmployeeAndDate($attendance->getEmployee(), $attendance->getAttendanceDate());
        if (!$workshift) {
            return;
        }

        $shiftment = $workshift->getShiftment();
        $attendance->setShiftment($shiftment);

        if ($attendance->isAbsent() || !($attendance->getCheckOut() || $attendance->getCheckIn())) {
            $attendance->setCheckIn(null);
            $attendance->setCheckOut(null);
            $attendance->setEarlyIn(0);
            $attendance->setEarlyOut(0);
            $attendance->setLateIn(0);
            $attendance->setLateOut(0);

            return;
        }
        $attendance->setReason(null);

        $this->doCalculate($attendance, $shiftment);
    }

    /**
     * @param AttendanceInterface $attendance
     * @param ShiftmentInterface  $shiftment
     */
    private function doCalculate(AttendanceInterface $attendance, ShiftmentInterface $shiftment): void
    {
        /** @var \DateTime $startHour */
        $startHour = $shiftment->getStartHour();
        /** @var \DateTime $endHour */
        $endHour = $shiftment->getEndHour();

        $checkIn = $attendance->getCheckIn() ?? $shiftment->getStartHour();
        $attendance->setCheckIn($checkIn);

        $checkOut = $attendance->getCheckOut() ?? $shiftment->getEndHour();
        $attendance->setCheckOut($checkOut);

        if ($checkIn <= $startHour) {
            $delta = $startHour->getTimestamp() - $checkIn->getTimestamp();
            $minutes = round($delta / 60);

            $attendance->setEarlyIn((int) $minutes);
            $attendance->setLateIn(0);
        } else {
            $delta = $checkIn->getTimestamp() - $startHour->getTimestamp();
            $minutes = round($delta / 60);

            $attendance->setLateIn((int) $minutes);
            $attendance->setEarlyIn(0);
        }

        if ($checkOut >= $endHour) {
            $delta = $checkOut->getTimestamp() - $endHour->getTimestamp();
            $minutes = round($delta / 60);

            $attendance->setLateOut((int) $minutes);
            $attendance->setEarlyOut(0);
        } else {
            $delta = $endHour->getTimestamp() - $checkOut->getTimestamp();
            $minutes = round($delta / 60);

            $attendance->setEarlyOut((int) $minutes);
            $attendance->setLateOut(0);
        }
    }
}
