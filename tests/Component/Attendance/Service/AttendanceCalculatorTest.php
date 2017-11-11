<?php

namespace Tests\KejawenLab\Application\SemartHris\Component\Attendance\Service;

use KejawenLab\Application\SemartHris\Component\Attendance\Model\AttendanceInterface;
use KejawenLab\Application\SemartHris\Component\Attendance\Model\ShiftmentInterface;
use KejawenLab\Application\SemartHris\Component\Attendance\Model\WorkshiftInterface;
use KejawenLab\Application\SemartHris\Component\Attendance\Repository\WorkshiftRepositoryInterface;
use KejawenLab\Application\SemartHris\Component\Attendance\Service\AttendanceCalculator;
use KejawenLab\Application\SemartHris\Component\Employee\Model\EmployeeInterface;
use PHPUnit\Framework\TestCase;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@kejawenlab.com>
 */
class AttendanceCalculatorTest extends TestCase
{
    public function testAbsent()
    {
        $employee = $this->getMockBuilder(EmployeeInterface::class)->getMock();
        $date = new \DateTime();

        $shiftment = $this->getMockBuilder(ShiftmentInterface::class)->getMock();

        $workshift = $this->getMockBuilder(WorkshiftInterface::class)->getMock();
        $workshift->expects($this->once())->method('getShiftment')->willReturn($shiftment);

        $workshiftRepository = $this->getMockBuilder(WorkshiftRepositoryInterface::class)->getMock();
        $workshiftRepository->expects($this->once())->method('findByEmployeeAndDate')->with($employee, $date)->willReturn($workshift);

        $attendance = $this->getMockBuilder(AttendanceInterface::class)->getMock();
        $attendance->expects($this->once())->method('getEmployee')->willReturn($employee);
        $attendance->expects($this->once())->method('getAttendanceDate')->willReturn($date);
        $attendance->expects($this->once())->method('setShiftment')->with($shiftment);
        $attendance->expects($this->once())->method('isAbsent')->willReturn(true);
        $attendance->expects($this->never())->method('setReason');

        $calculator = new AttendanceCalculator($workshiftRepository);
        $calculator->calculate($attendance);
    }
}
