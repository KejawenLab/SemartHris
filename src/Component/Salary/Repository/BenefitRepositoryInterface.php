<?php

declare(strict_types=1);

namespace KejawenLab\Application\SemartHris\Component\Salary\Repository;

use KejawenLab\Application\SemartHris\Component\Employee\Model\EmployeeInterface;
use KejawenLab\Application\SemartHris\Component\Salary\Model\BenefitInterface;
use KejawenLab\Application\SemartHris\Component\Salary\Model\ComponentInterface;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
interface BenefitRepositoryInterface
{
    /**
     * @param EmployeeInterface $employee
     *
     * @return BenefitInterface[]
     */
    public function findFixedByEmployee(EmployeeInterface $employee): array;

    /**
     * @param EmployeeInterface  $employee
     * @param ComponentInterface $component
     *
     * @return BenefitInterface|null
     */
    public function findByEmployeeAndComponent(EmployeeInterface $employee, ComponentInterface $component): ? BenefitInterface;

    /**
     * @param BenefitInterface $benefit
     */
    public function update(BenefitInterface $benefit): void;
}
