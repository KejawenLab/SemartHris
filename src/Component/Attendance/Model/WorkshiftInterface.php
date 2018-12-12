<?php

declare(strict_types=1);

namespace KejawenLab\Application\SemartHris\Component\Attendance\Model;

use KejawenLab\Application\SemartHris\Component\Employee\Model\EmployeeInterface;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
interface WorkshiftInterface
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
     * @return null|string
     */
    public function getDescription(): ? string;

    /**
     * @param null|string $description
     */
    public function setDescription(?string $description): void;

    /**
     * @return \DateTimeInterface
     */
    public function getStartDate(): ? \DateTimeInterface;

    /**
     * @param \DateTimeInterface|null $startDate
     */
    public function setStartDate(?\DateTimeInterface $startDate): void;

    /**
     * @return \DateTimeInterface|null
     */
    public function getEndDate(): ? \DateTimeInterface;

    /**
     * @param \DateTimeInterface|null $endDate
     */
    public function setEndDate(?\DateTimeInterface $endDate): void;
}
