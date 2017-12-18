<?php

declare(strict_types=1);

namespace KejawenLab\Application\SemartHris\Component\Attendance\Model;

use KejawenLab\Application\SemartHris\Component\Employee\Model\EmployeeInterface;
use KejawenLab\Application\SemartHris\Component\Reason\Model\ReasonInterface;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
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
    public function setEmployee(?EmployeeInterface $employee): void;

    /**
     * @return null|ShiftmentInterface
     */
    public function getShiftment(): ? ShiftmentInterface;

    /**
     * @param ShiftmentInterface|null $shiftment
     */
    public function setShiftment(?ShiftmentInterface $shiftment): void;

    /**
     * @return \DateTimeInterface
     */
    public function getAttendanceDate(): ? \DateTimeInterface;

    /**
     * @param \DateTimeInterface|null $date
     */
    public function setAttendanceDate(?\DateTimeInterface $date): void;

    /**
     * @return null|string
     */
    public function getDescription(): ? string;

    /**
     * @param null|string $description
     */
    public function setDescription(?string $description): void;

    /**
     * @return \DateTimeInterface|null
     */
    public function getCheckIn(): ? \DateTimeInterface;

    /**
     * @param \DateTimeInterface|null $time
     */
    public function setCheckIn(?\DateTimeInterface $time): void;

    /**
     * @return \DateTimeInterface|null
     */
    public function getCheckOut(): ? \DateTimeInterface;

    /**
     * @param \DateTimeInterface|null $time
     */
    public function setCheckOut(?\DateTimeInterface $time): void;

    /**
     * @return int
     */
    public function getEarlyIn(): int;

    /**
     * @param int $minutes
     */
    public function setEarlyIn(int $minutes): void;

    /**
     * @return int
     */
    public function getEarlyOut(): int;

    /**
     * @param int $minutes
     */
    public function setEarlyOut(int $minutes): void;

    /**
     * @return int
     */
    public function getLateIn(): int;

    /**
     * @param int $minutes
     */
    public function setLateIn(int $minutes): void;

    /**
     * @return int
     */
    public function getLateOut(): int;

    /**
     * @param int $minutes
     */
    public function setLateOut(int $minutes): void;

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
    public function getReason(): ? ReasonInterface;

    /**
     * @param ReasonInterface $reason
     */
    public function setReason(?ReasonInterface $reason): void;
}
