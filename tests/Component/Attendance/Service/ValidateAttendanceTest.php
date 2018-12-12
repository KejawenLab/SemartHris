<?php

namespace Tests\KejawenLab\Application\SemartHris\Component\Attendance\Service;

use KejawenLab\Application\SemartHris\Component\Attendance\Service\ValidateAttendance;
use KejawenLab\Application\SemartHris\Component\Reason\Model\ReasonInterface;
use PHPUnit\Framework\TestCase;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class ValidateAttendanceTest extends TestCase
{
    public function testInvalid()
    {
        $attendance = new AttendanceStub();

        $this->assertSame(false, ValidateAttendance::validate($attendance));

        $attendance->setAbsent(true);
        $this->assertSame(false, ValidateAttendance::validate($attendance));

        $attendance->setCheckIn(new \DateTime());
        $this->assertSame(false, ValidateAttendance::validate($attendance));
    }

    public function testValid()
    {
        $attendance = new AttendanceStub();

        $attendance->setCheckIn(new \DateTime());
        $attendance->setCheckOut(new \DateTime());
        $this->assertSame(true, ValidateAttendance::validate($attendance));

        $attendance->setAbsent(true);
        $attendance->setReason($this->getMockBuilder(ReasonInterface::class)->getMock());
        $this->assertSame(true, ValidateAttendance::validate($attendance));
    }
}
