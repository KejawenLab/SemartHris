<?php

declare(strict_types=1);

namespace KejawenLab\Application\SemartHris\Component\Salary\Model;

use KejawenLab\Application\SemartHris\Component\Employee\Model\EmployeeInterface;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
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
     * @param null|string $value
     */
    public function setBenefitValue(?string $value): void;

    /**
     * @return null|string
     */
    public function getBenefitKey(): ? string;
}
