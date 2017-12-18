<?php

declare(strict_types=1);

namespace KejawenLab\Application\SemartHris\Component\Salary\Model;

use KejawenLab\Application\SemartHris\Component\Contract\Model\ContractInterface;
use KejawenLab\Application\SemartHris\Component\Employee\Model\EmployeeInterface;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
interface BenefitHistoryInterface
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
     * @return ComponentInterface|null
     */
    public function getComponent(): ? ComponentInterface;

    /**
     * @param ComponentInterface|null $component
     */
    public function setComponent(?ComponentInterface $component): void;

    /**
     * @return ContractInterface|null
     */
    public function getContract(): ? ContractInterface;

    /**
     * @param ContractInterface|null $contract
     */
    public function setContract(?ContractInterface $contract): void;

    /**
     * @return null|string
     */
    public function getNewBenefitValue(): ? string;

    /**
     * @param string|null $value
     */
    public function setNewBenefitValue(?string $value): void;

    /**
     * @return null|string
     */
    public function getOldBenefitValue(): ? string;

    /**
     * @param string|null $value
     */
    public function setOldBenefitValue(?string $value): void;

    /**
     * @return null|string
     */
    public function getDescription(): ? string;

    /**
     * @param string|null $description
     */
    public function setDescription(?string $description): void;

    /**
     * @return null|string
     */
    public function getBenefitKey(): ? string;
}
