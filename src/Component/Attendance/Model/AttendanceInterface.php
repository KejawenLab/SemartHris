<?php

namespace KejawenLab\Application\SemartHris\Component\Attendance\Model;

use KejawenLab\Application\SemartHris\Component\Employee\Model\EmployeeInterface;
use KejawenLab\Application\SemartHris\Component\Reason\Model\ReasonInterface;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@kejawenlab.com>
 */
interface AttendanceInterface
{
    /**
     * @return string
     */
    public function getId(): string;

    /**
     * @return null|EmployeeInterface
     */
    public function getEmployee(): ? EmployeeInterface;

    /**
     * @param EmployeeInterface|null $employee
     */
    public function setEmployee(EmployeeInterface $employee = null): void;

    /**
     * @return null|ShiftmentInterface
     */
    public function getShiftment(): ? ShiftmentInterface;

    /**
     * @param ShiftmentInterface|null $shiftment
     */
    public function setShiftment(ShiftmentInterface $shiftment = null): void;

    /**
     * @return \DateTimeInterface
     */
    public function getAttendanceDate(): ? \DateTimeInterface;

    /**
     * @param \DateTimeInterface|null $date
     */
    public function setAttendanceDate(\DateTimeInterface $date = null): void;

    /**
     * @return null|string
     */
    public function getDescription(): ? string;

    /**
     * @param string $description
     */
    public function setDescription(string $description): void;

    /**
     * @return \DateTimeInterface
     */
    public function getCheckIn(): \DateTimeInterface;

    /**
     * @param \DateTimeInterface $time
     */
    public function setCheckIn(\DateTimeInterface $time): void;

    /**
     * @return \DateTimeInterface
     */
    public function getCheckOut(): \DateTimeInterface;

    /**
     * @param \DateTimeInterface $time
     */
    public function setCheckOut(\DateTimeInterface $time): void;

    /**
     * @return \DateTimeInterface
     */
    public function getEarlyIn(): \DateTimeInterface;

    /**
     * @param \DateTimeInterface $time
     */
    public function setEarlyIn(\DateTimeInterface $time): void;

    /**
     * @return \DateTimeInterface
     */
    public function getEarlyOut(): \DateTimeInterface;

    /**
     * @param \DateTimeInterface $time
     */
    public function setEarlyOut(\DateTimeInterface $time): void;

    /**
     * @return \DateTimeInterface
     */
    public function getLateIn(): \DateTimeInterface;

    /**
     * @param \DateTimeInterface $time
     */
    public function setLateIn(\DateTimeInterface $time): void;

    /**
     * @return \DateTimeInterface
     */
    public function getLateOut(): \DateTimeInterface;

    /**
     * @param \DateTimeInterface $time
     */
    public function setLateOut(\DateTimeInterface $time): void;

    /**
     * @return bool
     */
    public function isAbsent(): bool;

    /**
     * @param bool $isAbsent
     */
    public function setAbsent(bool $isAbsent): void;

    /**
     * @return null|ReasonInterface
     */
    public function getAbsentReason(): ? ReasonInterface;

    /**
     * @param ReasonInterface $reason
     */
    public function setAbsentReason(ReasonInterface $reason = null): void;
}
