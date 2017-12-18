<?php

declare(strict_types=1);

namespace KejawenLab\Application\SemartHris\Component\Leave\Service;

use KejawenLab\Application\SemartHris\Component\Attendance\Repository\AttendanceRepositoryInterface;
use KejawenLab\Application\SemartHris\Component\Leave\Model\LeaveInterface;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class SetAbsentWhenLeaveIsSubmited
{
    /**
     * @var AttendanceRepositoryInterface
     */
    private $attendanceRepository;

    /**
     * @param AttendanceRepositoryInterface $attendanceRepository
     */
    public function __construct(AttendanceRepositoryInterface $attendanceRepository)
    {
        $this->attendanceRepository = $attendanceRepository;
    }

    /**
     * @param LeaveInterface $leave
     */
    public function setAbsent(LeaveInterface $leave): void
    {
        /** @var \DateTime $date */
        $date = $leave->getLeaveDate();
        $date->sub(new \DateInterval('P1D'));
        $amount = $leave->getAmount();

        for ($i = 0; $i < $amount; ++$i) {
            $date->add(new \DateInterval('P1D'));
            $attendance = $this->attendanceRepository->createNew($leave->getEmployee(), $date);
            $attendance->setAbsent(true);
            $attendance->setReason($leave->getReason());
            $attendance->setDescription($leave->getDescription());

            $this->attendanceRepository->update($attendance);
        }
    }
}
