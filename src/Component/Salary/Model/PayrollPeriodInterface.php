<?php

namespace KejawenLab\Application\SemartHris\Component\Salary\Model;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@kejawenlab.com>
 */
interface PayrollPeriodInterface
{
    /**
     * @return string
     */
    public function getId(): string;

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
     * @return bool
     */
    public function isClosed(): bool;
}
