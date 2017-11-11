<?php

namespace KejawenLab\Application\SemartHris\Component\Salary\Model;

use KejawenLab\Application\SemartHris\Component\Employee\Model\EmployeeInterface;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@kejawenlab.com>
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
     * @return null|string
     */
    public function getBenefitValue(): ? string;

    /**
     * @param string|null $value
     */
    public function setBenefitValue(?string $value): void;

    /**
     * @return null|string
     */
    public function getDescription(): ? string;

    /**
     * @param string|null $description
     */
    public function setDescription(?string $description): void;
}
