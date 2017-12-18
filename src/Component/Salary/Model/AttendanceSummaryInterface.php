<?php

declare(strict_types=1);

namespace KejawenLab\Application\SemartHris\Component\Salary\Model;

use KejawenLab\Application\SemartHris\Component\Employee\Model\EmployeeInterface;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
interface AttendanceSummaryInterface
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
     * @return int|null
     */
    public function getMonth(): int;

    /**
     * @param int|null $month
     */
    public function setMonth(int $month): void;

    /**
     * @return int|null
     */
    public function getYear(): int;

    /**
     * @param int|null $year
     */
    public function setYear(int $year): void;

    /**
     * @return int|null
     */
    public function getTotalWorkday(): ? int;

    /**
     * @param int $totalWorkday
     */
    public function setTotalWorkday(int $totalWorkday): void;

    /**
     * @return int|null
     */
    public function getTotalIn(): ? int;

    /**
     * @param int $totalIn
     */
    public function setTotalIn(int $totalIn): void;

    /**
     * @return int|null
     */
    public function getTotalLoyality(): ? int;

    /**
     * @param int $totalLoyality
     */
    public function setTotalLoyality(int $totalLoyality): void;

    /**
     * @return int|null
     */
    public function getTotalAbsent(): ? int;

    /**
     * @param int $totalAbsent
     */
    public function setTotalAbsent(int $totalAbsent): void;

    /**
     * @return int|null
     */
    public function getTotalOvertime(): ? int;

    /**
     * @param int $totalOvertime
     */
    public function setTotalOvertime(int $totalOvertime): void;
}
