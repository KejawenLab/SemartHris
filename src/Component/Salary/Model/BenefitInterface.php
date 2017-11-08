<?php

namespace KejawenLab\Application\SemartHris\Component\Salary\Model;

use KejawenLab\Application\SemartHris\Component\Employee\Model\EmployeeInterface;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@kejawenlab.com>
 */
interface BenefitInterface
{
    /**
     * @return string
     */
    public function getId(): string;

    /**
     * @return EmployeeInterface|null
     */
    public function getPayroll(): ? EmployeeInterface;

    /**
     * @param EmployeeInterface|null $employee
     */
    public function setPayroll(EmployeeInterface $employee = null): void;

    /**
     * @return ComponentInterface|null
     */
    public function getComponent(): ? ComponentInterface;

    /**
     * @param ComponentInterface|null $component
     */
    public function setComponent(ComponentInterface $component = null): void;

    /**
     * @return null|string
     */
    public function getBenefitValue(): ? string;

    /**
     * @param string $value
     */
    public function setBenefitValue(string $value): void;
}
