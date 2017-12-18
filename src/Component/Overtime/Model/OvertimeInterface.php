<?php

declare(strict_types=1);

namespace KejawenLab\Application\SemartHris\Component\Overtime\Model;

use KejawenLab\Application\SemartHris\Component\Attendance\Model\ShiftmentInterface;
use KejawenLab\Application\SemartHris\Component\Employee\Model\EmployeeInterface;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
interface OvertimeInterface
{
    /**
     * @return string
     */
    public function getId(): string;

    /**
     * @return EmployeeInterface|null
     */
    public function getEmployee(): ? EmployeeInterface;

    /**
     * @param EmployeeInterface $employee
     */
    public function setEmployee(EmployeeInterface $employee): void;

    /**
     * @return null|ShiftmentInterface
     */
    public function getShiftment(): ? ShiftmentInterface;

    /**
     * @param ShiftmentInterface|null $shiftment
     */
    public function setShiftment(?ShiftmentInterface $shiftment): void;

    /**
     * @return \DateTimeInterface|null
     */
    public function getOvertimeDate(): ? \DateTimeInterface;

    /**
     * @param \DateTimeInterface|null $date
     */
    public function setOvertimeDate(?\DateTimeInterface $date): void;

    /**
     * @return \DateTimeInterface|null
     */
    public function getStartHour(): ? \DateTimeInterface;

    /**
     * @param \DateTimeInterface|null $time
     */
    public function setStartHour(?\DateTimeInterface $time): void;

    /**
     * @return \DateTimeInterface|null
     */
    public function getEndHour(): ? \DateTimeInterface;

    /**
     * @param \DateTimeInterface|null $time
     */
    public function setEndHour(?\DateTimeInterface $time): void;

    /**
     * @return float|null
     */
    public function getRawValue(): ? float;

    /**
     * @param float $overtime
     */
    public function setRawValue(float $overtime): void;

    /**
     * @return float|null
     */
    public function getCalculatedValue(): ? float;

    /**
     * @param float $overtime
     */
    public function setCalculatedValue(float $overtime): void;

    /**
     * @return bool
     */
    public function isHoliday(): bool;

    /**
     * @param bool $holiday
     */
    public function setHoliday(bool $holiday): void;

    /**
     * @return bool
     */
    public function isOverday(): bool;

    /**
     * @param bool $overday
     */
    public function setOverday(bool $overday): void;

    /**
     * @return bool
     */
    public function isApproved(): bool;

    /**
     * @return EmployeeInterface|null
     */
    public function getApprovedBy(): ? EmployeeInterface;

    /**
     * @param EmployeeInterface|null $employee
     */
    public function setApprovedBy(?EmployeeInterface $employee): void;

    /**
     * @return null|string
     */
    public function getDescription(): ? string;

    /**
     * @param string|null $description
     */
    public function setDescription(?string $description): void;
}
