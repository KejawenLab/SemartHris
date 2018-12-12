<?php

namespace Tests\KejawenLab\Application\SemartHris\Component\Attendance\Service;

use KejawenLab\Application\SemartHris\Component\Attendance\Model\AttendanceInterface;
use KejawenLab\Application\SemartHris\Component\Attendance\Model\ShiftmentInterface;
use KejawenLab\Application\SemartHris\Component\Employee\Model\EmployeeInterface;
use KejawenLab\Application\SemartHris\Component\Reason\Model\ReasonInterface;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class AttendanceStub implements AttendanceInterface
{
    /**
     * @var string
     */
    private $id;

    /**
     * @var EmployeeInterface
     */
    private $employee;

    /**
     * @var ShiftmentInterface
     */
    private $shiftment;

    /**
     * @var \DateTimeInterface
     */
    private $attendanceDate;

    /**
     * @var string
     */
    private $description;

    /**
     * @var \DateTimeInterface
     */
    private $checkIn;

    /**
     * @var \DateTimeInterface
     */
    private $checkOut;

    /**
     * @var int
     */
    private $earlyIn;

    /**
     * @var int
     */
    private $earlyOut;

    /**
     * @var int
     */
    private $lateIn;

    /**
     * @var int
     */
    private $lateOut;

    /**
     * @var bool
     */
    private $absent;

    /**
     * @var ReasonInterface
     */
    private $reason;

    public function __construct()
    {
        $this->earlyIn = 0;
        $this->earlyOut = 0;
        $this->lateIn = 0;
        $this->lateOut = 0;
        $this->absent = false;
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return (string) $this->id;
    }

    /**
     * @return EmployeeInterface|null
     */
    public function getEmployee(): ? EmployeeInterface
    {
        return $this->employee;
    }

    /**
     * @param EmployeeInterface|null $employee
     */
    public function setEmployee(?EmployeeInterface $employee): void
    {
        $this->employee = $employee;
    }

    /**
     * @return ShiftmentInterface|null
     */
    public function getShiftment(): ? ShiftmentInterface
    {
        return $this->shiftment;
    }

    /**
     * @param ShiftmentInterface|null $shiftment
     */
    public function setShiftment(?ShiftmentInterface $shiftment): void
    {
        $this->shiftment = $shiftment;
    }

    /**
     * @return \DateTimeInterface|null
     */
    public function getAttendanceDate(): ? \DateTimeInterface
    {
        return $this->attendanceDate;
    }

    /**
     * @param \DateTimeInterface|null $attendanceDate
     */
    public function setAttendanceDate(?\DateTimeInterface $attendanceDate): void
    {
        $this->attendanceDate = $attendanceDate;
    }

    /**
     * @return null|string
     */
    public function getDescription(): ? string
    {
        return $this->description;
    }

    /**
     * @param null|string $description
     */
    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }

    /**
     * @return \DateTimeInterface|null
     */
    public function getCheckIn(): ? \DateTimeInterface
    {
        return $this->checkIn;
    }

    /**
     * @param \DateTimeInterface|null $checkIn
     */
    public function setCheckIn(?\DateTimeInterface $checkIn): void
    {
        $this->checkIn = $checkIn;
    }

    /**
     * @return \DateTimeInterface|null
     */
    public function getCheckOut(): ? \DateTimeInterface
    {
        return $this->checkOut;
    }

    /**
     * @param \DateTimeInterface|null $checkOut
     */
    public function setCheckOut(?\DateTimeInterface $checkOut): void
    {
        $this->checkOut = $checkOut;
    }

    /**
     * @return int
     */
    public function getEarlyIn(): int
    {
        return $this->earlyIn;
    }

    /**
     * @param int $earlyIn
     */
    public function setEarlyIn(int $earlyIn): void
    {
        $this->earlyIn = $earlyIn;
    }

    /**
     * @return int
     */
    public function getEarlyOut(): int
    {
        return $this->earlyOut;
    }

    /**
     * @param int $earlyOut
     */
    public function setEarlyOut(int $earlyOut): void
    {
        $this->earlyOut = $earlyOut;
    }

    /**
     * @return int
     */
    public function getLateIn(): int
    {
        return $this->lateIn;
    }

    /**
     * @param int $lateIn
     */
    public function setLateIn(int $lateIn): void
    {
        $this->lateIn = $lateIn;
    }

    /**
     * @return int
     */
    public function getLateOut(): int
    {
        return $this->lateOut;
    }

    /**
     * @param int $lateOut
     */
    public function setLateOut(int $lateOut): void
    {
        $this->lateOut = $lateOut;
    }

    /**
     * @return bool
     */
    public function isAbsent(): bool
    {
        return (bool) $this->absent;
    }

    /**
     * @param bool $absent
     */
    public function setAbsent(bool $absent): void
    {
        $this->absent = $absent;
    }

    /**
     * @return ReasonInterface|null
     */
    public function getReason(): ? ReasonInterface
    {
        return $this->reason;
    }

    /**
     * @param ReasonInterface $reason
     */
    public function setReason(?ReasonInterface $reason): void
    {
        $this->reason = $reason;
    }
}
