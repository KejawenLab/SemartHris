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
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class AttendanceCalculatorTest extends TestCase
{
    /**
     * @var EmployeeInterface
     */
    private $employee;

    /**
     * @var \DateTimeInterface
     */
    private $attendanceDate;

    /**
     * @var \MockTestInterface
     */
    private $shiftment;

    /**
     * @var WorkshiftInterface
     */
    private $workshift;

    /**
     * @var WorkshiftRepositoryInterface
     */
    private $workshiftRepository;

    public function setUp()
    {
        $this->employee = $this->getMockBuilder(EmployeeInterface::class)->getMock();
        $this->attendanceDate = new \DateTime();

        $this->shiftment = $this->getMockBuilder(ShiftmentInterface::class)->getMock();

        $this->workshift = $this->getMockBuilder(WorkshiftInterface::class)->getMock();
        $this->workshift->expects($this->once())->method('getShiftment')->willReturn($this->shiftment);

        $this->workshiftRepository = $this->getMockBuilder(WorkshiftRepositoryInterface::class)->getMock();
        $this->workshiftRepository->expects($this->once())->method('findByEmployeeAndDate')->with($this->employee, $this->attendanceDate)->willReturn($this->workshift);
    }

    public function testAbsent()
    {
        $attendance = $this->getMockBuilder(AttendanceInterface::class)->getMock();
        $attendance->expects($this->once())->method('getEmployee')->willReturn($this->employee);
        $attendance->expects($this->once())->method('getAttendanceDate')->willReturn($this->attendanceDate);
        $attendance->expects($this->once())->method('setShiftment')->with($this->shiftment);
        $attendance->expects($this->once())->method('isAbsent')->willReturn(true);
        $attendance->expects($this->never())->method('setReason');

        $calculator = new AttendanceCalculator($this->workshiftRepository);
        $calculator->calculate($attendance);
    }

    public function testNotCheckInAndCheckout()
    {
        $attendance = $this->getMockBuilder(AttendanceInterface::class)->getMock();
        $attendance->expects($this->once())->method('getEmployee')->willReturn($this->employee);
        $attendance->expects($this->once())->method('getAttendanceDate')->willReturn($this->attendanceDate);
        $attendance->expects($this->once())->method('setShiftment')->with($this->shiftment);
        $attendance->expects($this->once())->method('isAbsent')->willReturn(false);
        $attendance->expects($this->once())->method('getCheckIn')->willReturn(null);
        $attendance->expects($this->once())->method('getCheckOut')->willReturn(null);
        $attendance->expects($this->never())->method('setReason');

        $calculator = new AttendanceCalculator($this->workshiftRepository);
        $calculator->calculate($attendance);
    }

    public function testWorkInWithoutCheckIn()
    {
        $attendance = $this->getMockBuilder(AttendanceInterface::class)->getMock();
        $attendance->expects($this->once())->method('getEmployee')->willReturn($this->employee);
        $attendance->expects($this->once())->method('getAttendanceDate')->willReturn($this->attendanceDate);
        $attendance->expects($this->once())->method('setShiftment')->with($this->shiftment);
        $attendance->expects($this->once())->method('isAbsent')->willReturn(false);
        $attendance->expects($this->atLeastOnce())->method('getCheckIn')->willReturn(null);
        $attendance->expects($this->atLeastOnce())->method('getCheckOut')->willReturn($this->attendanceDate);
        $attendance->expects($this->once())->method('setReason');

        $this->setUpShiftment($this->attendanceDate, $this->attendanceDate);

        $calculator = new AttendanceCalculator($this->workshiftRepository);
        $calculator->calculate($attendance);
    }

    public function testAttendanceCalculatorWithoutCheckout()
    {
        $checkIn = \DateTime::createFromFormat('Y-m-d H:i:s', sprintf('%s 08:00:00', $this->attendanceDate->format('Y-m-d')));
        $checkOut = \DateTime::createFromFormat('Y-m-d H:i:s', sprintf('%s 17:00:00', $this->attendanceDate->format('Y-m-d')));

        $this->setUpShiftment($checkIn, $checkOut);

        $attendance = new AttendanceStub();
        $attendance->setEmployee($this->employee);
        $attendance->setShiftment($this->shiftment);
        $attendance->setAttendanceDate($this->attendanceDate);
        $attendance->setCheckIn($checkIn);

        $calculator = new AttendanceCalculator($this->workshiftRepository);
        $calculator->calculate($attendance);

        $this->assertEquals(0, $attendance->getEarlyIn());
        $this->assertEquals(0, $attendance->getEarlyOut());
        $this->assertEquals(0, $attendance->getLateIn());
        $this->assertEquals(0, $attendance->getLateOut());
        $this->assertEquals($checkOut->getTimestamp(), $attendance->getCheckOut()->getTimestamp());
    }

    public function testAttendanceCalculatorWithoutCheckIn()
    {
        $checkIn = \DateTime::createFromFormat('Y-m-d H:i:s', sprintf('%s 08:00:00', $this->attendanceDate->format('Y-m-d')));
        $checkOut = \DateTime::createFromFormat('Y-m-d H:i:s', sprintf('%s 17:00:00', $this->attendanceDate->format('Y-m-d')));

        $this->setUpShiftment($checkIn, $checkOut);

        $attendance = new AttendanceStub();
        $attendance->setEmployee($this->employee);
        $attendance->setShiftment($this->shiftment);
        $attendance->setAttendanceDate($this->attendanceDate);
        $attendance->setCheckOut($checkOut);

        $calculator = new AttendanceCalculator($this->workshiftRepository);
        $calculator->calculate($attendance);

        $this->assertEquals(0, $attendance->getEarlyIn());
        $this->assertEquals(0, $attendance->getEarlyOut());
        $this->assertEquals(0, $attendance->getLateIn());
        $this->assertEquals(0, $attendance->getLateOut());
        $this->assertEquals($checkIn->getTimestamp(), $attendance->getCheckIn()->getTimestamp());
    }

    public function testAttendanceCalculatorEaryIn()
    {
        $startHour = \DateTime::createFromFormat('Y-m-d H:i:s', sprintf('%s 08:00:00', $this->attendanceDate->format('Y-m-d')));
        $checkIn = \DateTime::createFromFormat('Y-m-d H:i:s', sprintf('%s 07:30:00', $this->attendanceDate->format('Y-m-d')));
        $endHour = \DateTime::createFromFormat('Y-m-d H:i:s', sprintf('%s 17:00:00', $this->attendanceDate->format('Y-m-d')));

        $this->setUpShiftment($startHour, $endHour);

        $attendance = new AttendanceStub();
        $attendance->setEmployee($this->employee);
        $attendance->setShiftment($this->shiftment);
        $attendance->setAttendanceDate($this->attendanceDate);
        $attendance->setCheckIn($checkIn);
        $attendance->setCheckOut($endHour);

        $calculator = new AttendanceCalculator($this->workshiftRepository);
        $calculator->calculate($attendance);

        $this->assertEquals(30, $attendance->getEarlyIn());
        $this->assertEquals(0, $attendance->getEarlyOut());
        $this->assertEquals(0, $attendance->getLateIn());
        $this->assertEquals(0, $attendance->getLateOut());
        $this->assertEquals($checkIn->getTimestamp(), $attendance->getCheckIn()->getTimestamp());
        $this->assertEquals($endHour->getTimestamp(), $attendance->getCheckOut()->getTimestamp());
    }

    public function testAttendanceCalculatorLateIn()
    {
        $startHour = \DateTime::createFromFormat('Y-m-d H:i:s', sprintf('%s 08:00:00', $this->attendanceDate->format('Y-m-d')));
        $checkIn = \DateTime::createFromFormat('Y-m-d H:i:s', sprintf('%s 08:15:00', $this->attendanceDate->format('Y-m-d')));
        $endHour = \DateTime::createFromFormat('Y-m-d H:i:s', sprintf('%s 17:00:00', $this->attendanceDate->format('Y-m-d')));

        $this->setUpShiftment($startHour, $endHour);

        $attendance = new AttendanceStub();
        $attendance->setEmployee($this->employee);
        $attendance->setShiftment($this->shiftment);
        $attendance->setAttendanceDate($this->attendanceDate);
        $attendance->setCheckIn($checkIn);
        $attendance->setCheckOut($endHour);

        $calculator = new AttendanceCalculator($this->workshiftRepository);
        $calculator->calculate($attendance);

        $this->assertEquals(0, $attendance->getEarlyIn());
        $this->assertEquals(0, $attendance->getEarlyOut());
        $this->assertEquals(15, $attendance->getLateIn());
        $this->assertEquals(0, $attendance->getLateOut());
        $this->assertEquals($checkIn->getTimestamp(), $attendance->getCheckIn()->getTimestamp());
        $this->assertEquals($endHour->getTimestamp(), $attendance->getCheckOut()->getTimestamp());
    }

    public function testAttendanceCalculatorEaryOut()
    {
        $startHour = \DateTime::createFromFormat('Y-m-d H:i:s', sprintf('%s 08:00:00', $this->attendanceDate->format('Y-m-d')));
        $endHour = \DateTime::createFromFormat('Y-m-d H:i:s', sprintf('%s 17:00:00', $this->attendanceDate->format('Y-m-d')));
        $checkOut = \DateTime::createFromFormat('Y-m-d H:i:s', sprintf('%s 16:45:00', $this->attendanceDate->format('Y-m-d')));

        $this->setUpShiftment($startHour, $endHour);

        $attendance = new AttendanceStub();
        $attendance->setEmployee($this->employee);
        $attendance->setShiftment($this->shiftment);
        $attendance->setAttendanceDate($this->attendanceDate);
        $attendance->setCheckIn($startHour);
        $attendance->setCheckOut($checkOut);

        $calculator = new AttendanceCalculator($this->workshiftRepository);
        $calculator->calculate($attendance);

        $this->assertEquals(0, $attendance->getEarlyIn());
        $this->assertEquals(15, $attendance->getEarlyOut());
        $this->assertEquals(0, $attendance->getLateIn());
        $this->assertEquals(0, $attendance->getLateOut());
        $this->assertEquals($startHour->getTimestamp(), $attendance->getCheckIn()->getTimestamp());
        $this->assertEquals($checkOut->getTimestamp(), $attendance->getCheckOut()->getTimestamp());
    }

    public function testAttendanceCalculatorLateOut()
    {
        $startHour = \DateTime::createFromFormat('Y-m-d H:i:s', sprintf('%s 08:00:00', $this->attendanceDate->format('Y-m-d')));
        $endHour = \DateTime::createFromFormat('Y-m-d H:i:s', sprintf('%s 17:00:00', $this->attendanceDate->format('Y-m-d')));
        $checkOut = \DateTime::createFromFormat('Y-m-d H:i:s', sprintf('%s 17:45:00', $this->attendanceDate->format('Y-m-d')));

        $this->setUpShiftment($startHour, $endHour);

        $attendance = new AttendanceStub();
        $attendance->setEmployee($this->employee);
        $attendance->setShiftment($this->shiftment);
        $attendance->setAttendanceDate($this->attendanceDate);
        $attendance->setCheckIn($startHour);
        $attendance->setCheckOut($checkOut);

        $calculator = new AttendanceCalculator($this->workshiftRepository);
        $calculator->calculate($attendance);

        $this->assertEquals(0, $attendance->getEarlyIn());
        $this->assertEquals(0, $attendance->getEarlyOut());
        $this->assertEquals(0, $attendance->getLateIn());
        $this->assertEquals(45, $attendance->getLateOut());
        $this->assertEquals($startHour->getTimestamp(), $attendance->getCheckIn()->getTimestamp());
        $this->assertEquals($checkOut->getTimestamp(), $attendance->getCheckOut()->getTimestamp());
    }

    public function testAttendanceCalculatorEaryInLateOut()
    {
        $startHour = \DateTime::createFromFormat('Y-m-d H:i:s', sprintf('%s 08:00:00', $this->attendanceDate->format('Y-m-d')));
        $checkIn = \DateTime::createFromFormat('Y-m-d H:i:s', sprintf('%s 07:30:00', $this->attendanceDate->format('Y-m-d')));
        $endHour = \DateTime::createFromFormat('Y-m-d H:i:s', sprintf('%s 17:00:00', $this->attendanceDate->format('Y-m-d')));
        $checkOut = \DateTime::createFromFormat('Y-m-d H:i:s', sprintf('%s 17:45:00', $this->attendanceDate->format('Y-m-d')));

        $this->setUpShiftment($startHour, $endHour);

        $attendance = new AttendanceStub();
        $attendance->setEmployee($this->employee);
        $attendance->setShiftment($this->shiftment);
        $attendance->setAttendanceDate($this->attendanceDate);
        $attendance->setCheckIn($checkIn);
        $attendance->setCheckOut($checkOut);

        $calculator = new AttendanceCalculator($this->workshiftRepository);
        $calculator->calculate($attendance);

        $this->assertEquals(30, $attendance->getEarlyIn());
        $this->assertEquals(0, $attendance->getEarlyOut());
        $this->assertEquals(0, $attendance->getLateIn());
        $this->assertEquals(45, $attendance->getLateOut());
        $this->assertEquals($checkIn->getTimestamp(), $attendance->getCheckIn()->getTimestamp());
        $this->assertEquals($checkOut->getTimestamp(), $attendance->getCheckOut()->getTimestamp());
    }

    /**
     * @param \DateTimeInterface $startHour
     * @param \DateTimeInterface $endHour
     */
    private function setUpShiftment(\DateTimeInterface $startHour, \DateTimeInterface $endHour)
    {
        $this->shiftment->expects($this->atLeastOnce())->method('getStartHour')->willReturn($startHour);
        $this->shiftment->expects($this->atLeastOnce())->method('getEndHour')->willReturn($endHour);
    }
}
