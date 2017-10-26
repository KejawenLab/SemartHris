<?php

namespace KejawenLab\Application\SemartHris\Component\Attendance\Service;

use KejawenLab\Application\SemartHris\Component\Attendance\Model\AttendanceInterface;
use KejawenLab\Application\SemartHris\Component\Attendance\Repository\WorkshiftRepositoryInterface;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@kejawenlab.com>
 */
final class AttendanceCalculator
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
        if (!$attendance->getCheckOut()) {
            return;
        }

        $workshift = $this->workshiftRepository->findByEmployeeAndDate($attendance->getEmployee(), $attendance->getAttendanceDate());
        if (!$workshift) {
            return;
        }

        if ($attendance->isAbsent()) {
            $attendance->setCheckIn(null);
            $attendance->setCheckOut(null);
            $attendance->setEarlyIn(0);
            $attendance->setEarlyOut(0);
            $attendance->setLateIn(0);
            $attendance->setLateOut(0);

            return;
        }

        $shiftment = $workshift->getShiftment();
        $attendance->setShiftment($shiftment);

        /** @var \DateTime $startHour */
        $startHour = $shiftment->getStartHour();
        /** @var \DateTime $endHour */
        $endHour = $shiftment->getEndHour();
        $checkIn = $attendance->getCheckIn();
        $checkOut = $attendance->getCheckOut();

        if ($checkIn <= $startHour) {
            $delta = $startHour->getTimestamp() - $checkIn->getTimestamp();
            $minutes = round($delta / 60);

            $attendance->setEarlyIn($minutes);
        } else {
            $delta = $checkIn->getTimestamp() - $startHour->getTimestamp();
            $minutes = round($delta / 60);

            $attendance->setLateIn($minutes);
        }

        if ($checkOut >= $endHour) {
            $delta = $checkOut->getTimestamp() - $endHour->getTimestamp();
            $minutes = round($delta / 60);

            $attendance->setLateOut($minutes);
        } else {
            $delta = $endHour->getTimestamp() - $checkOut->getTimestamp();
            $minutes = round($delta / 60);

            $attendance->setEarlyOut($minutes);
        }
    }
}
