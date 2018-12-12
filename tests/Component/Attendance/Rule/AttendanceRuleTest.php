<?php

namespace Tests\KejawenLab\Application\SemartHris\Component\Attendance\Rule;

use KejawenLab\Application\SemartHris\Component\Attendance\Model\AttendanceInterface;
use KejawenLab\Application\SemartHris\Component\Attendance\Rule\AttendanceRule;
use KejawenLab\Application\SemartHris\Component\Attendance\Rule\NotQualifiedException;
use KejawenLab\Application\SemartHris\Component\Attendance\Rule\RuleInterface;
use KejawenLab\Application\SemartHris\Component\Employee\Model\EmployeeInterface;
use PHPUnit\Framework\TestCase;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class AttendanceRuleTest extends TestCase
{
    public function testValidRule()
    {
        $attendance = $this->getMockBuilder(AttendanceInterface::class)->getMock();

        $firstRule = $this->getMockBuilder(RuleInterface::class)->getMock();
        $firstRule->expects($this->once())->method('apply')->willThrowException(new NotQualifiedException());

        $secondRule = $this->getMockBuilder(RuleInterface::class)->getMock();
        $secondRule->expects($this->once())->method('apply')->willReturn($attendance);

        $attendanceRule = new AttendanceRule([$firstRule, $secondRule, new ValidAttendanceRuleStub()]);

        $employee = $this->getMockBuilder(EmployeeInterface::class)->getMock();
        $date = new \DateTime();

        $this->assertInstanceOf(AttendanceInterface::class, $attendanceRule->apply($employee, $date));
    }

    public function testNothingRule()
    {
        $this->expectException(NotQualifiedException::class);

        $employee = $this->getMockBuilder(EmployeeInterface::class)->getMock();
        $date = new \DateTime();

        $attendanceRule = new AttendanceRule();
        $attendanceRule->apply($employee, $date);
    }

    public function testInvalidRule()
    {
        $this->expectException(\TypeError::class);

        (new AttendanceRule([new InvalidAttendanceRuleStub()]));
    }
}
