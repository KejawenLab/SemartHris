<?php

declare(strict_types=1);

namespace KejawenLab\Application\SemartHris\Component\Salary\Model;

use KejawenLab\Application\SemartHris\Component\Company\Model\CompanyInterface;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
interface PayrollPeriodInterface
{
    /**
     * @return string
     */
    public function getId(): string;

    /**
     * @return CompanyInterface|null
     */
    public function getCompany(): ? CompanyInterface;

    /**
     * @param CompanyInterface|null $company
     */
    public function setCompany(?CompanyInterface $company): void;

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
    public function getMonth(): int;

    /**
     * @param int|null $month
     */
    public function setMonth(int $month): void;

    /**
     * @return bool
     */
    public function isClosed(): bool;

    /**
     * @param bool $closed
     */
    public function setClosed(bool $closed): void;
}
