<?php

namespace Tests\KejawenLab\Application\SemartHris\Component\Attendance\Service;

use KejawenLab\Application\SemartHris\Component\Attendance\Model\AttendanceInterface;
use KejawenLab\Application\SemartHris\Component\Attendance\Repository\AttendanceRepositoryInterface;
use KejawenLab\Application\SemartHris\Component\Attendance\Repository\WorkshiftRepositoryInterface;
use KejawenLab\Application\SemartHris\Component\Attendance\Rule\RuleInterface;
use KejawenLab\Application\SemartHris\Component\Attendance\Service\AttendanceProcessor;
use KejawenLab\Application\SemartHris\Component\Employee\Model\EmployeeInterface;
use KejawenLab\Application\SemartHris\Component\Holiday\Repository\HolidayRepositoryInterface;
use KejawenLab\Application\SemartHris\Component\Reason\Repository\ReasonRepositoryInterface;
use PHPUnit\Framework\TestCase;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class AttendanceProcessorTest extends TestCase
{
    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $attendanceRule;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $attendanceRepository;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $holidayRepository;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $reasonRepository;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $workshiftRepository;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $employee;

    public function setUp()
    {
        $this->attendanceRule = $this->getMockBuilder(RuleInterface::class)->getMock();
        $this->attendanceRule->expects($this->atLeastOnce())->method('apply');

        $this->attendanceRepository = $this->getMockBuilder(AttendanceRepositoryInterface::class)->getMock();
        $this->attendanceRepository->expects($this->atLeast(28))->method('findByEmployeeAndDate');
        $this->attendanceRepository->expects($this->atLeast(28))->method('update');

        $this->holidayRepository = $this->getMockBuilder(HolidayRepositoryInterface::class)->getMock();
        $this->holidayRepository->expects($this->atLeastOnce())->method('isHoliday');

        $this->reasonRepository = $this->getMockBuilder(ReasonRepositoryInterface::class)->getMock();
        $this->workshiftRepository = $this->getMockBuilder(WorkshiftRepositoryInterface::class)->getMock();
        $this->employee = $this->getMockBuilder(EmployeeInterface::class)->getMock();
    }

    public function testProcessPartialMonth()
    {
        $processor = new AttendanceProcessor(
            $this->attendanceRule,
            $this->attendanceRepository,
            $this->holidayRepository,
            $this->reasonRepository,
            $this->workshiftRepository,
            'ABS',
            -1,
            AttendanceInterface::class
        );

        $processor->process($this->employee, new \DateTime());
    }

    public function testProcessFullMonth()
    {
        $processor = new AttendanceProcessor(
            $this->attendanceRule,
            $this->attendanceRepository,
            $this->holidayRepository,
            $this->reasonRepository,
            $this->workshiftRepository,
            'ABS',
            15,
            AttendanceInterface::class
        );

        $processor->process($this->employee, new \DateTime());
    }
}
