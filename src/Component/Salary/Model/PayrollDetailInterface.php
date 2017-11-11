<?php

namespace KejawenLab\Application\SemartHris\Component\Salary\Model;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@kejawenlab.com>
 */
interface PayrollDetailInterface
{
    /**
     * @return string
     */
    public function getId(): string;

    /**
     * @return PayrollInterface|null
     */
    public function getPayroll(): ? PayrollInterface;

    /**
     * @param PayrollInterface|null $payroll
     */
    public function setPayroll(?PayrollInterface $payroll): void;

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
     * @param string $benefit
     */
    public function setBenefitValue(string $benefit): void;
}
