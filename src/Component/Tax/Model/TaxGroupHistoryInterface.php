<?php

declare(strict_types=1);

namespace KejawenLab\Application\SemartHris\Component\Tax\Model;

use KejawenLab\Application\SemartHris\Component\Employee\Model\EmployeeInterface;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
interface TaxGroupHistoryInterface
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
     * @param EmployeeInterface|null $employee
     */
    public function setEmployee(?EmployeeInterface $employee): void;

    /**
     * @return null|string
     */
    public function getOldTaxGroup(): ? string;

    /**
     * @param null|string $taxGroup
     */
    public function setOldTaxGroup(?string $taxGroup): void;

    /**
     * @return null|string
     */
    public function getNewTaxGroup(): ? string;

    /**
     * @param null|string $taxGroup
     */
    public function setNewTaxGroup(?string $taxGroup): void;

    /**
     * @return null|string
     */
    public function getOldRiskRatio(): ? string;

    /**
     * @param null|string $riskRatio
     */
    public function setOldRiskRatio(?string $riskRatio): void;

    /**
     * @return null|string
     */
    public function getNewRiskRatio(): ? string;

    /**
     * @param null|string $riskRatio
     */
    public function setNewRiskRatio(?string $riskRatio): void;
}
