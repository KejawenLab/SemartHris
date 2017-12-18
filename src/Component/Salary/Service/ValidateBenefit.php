<?php

declare(strict_types=1);

namespace KejawenLab\Application\SemartHris\Component\Salary\Service;

use KejawenLab\Application\SemartHris\Component\Employee\Model\EmployeeInterface;
use KejawenLab\Application\SemartHris\Component\Salary\Repository\PayrollRepositoryInterface;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class ValidateBenefit
{
    /**
     * @var PayrollRepositoryInterface
     */
    private $repository;

    /**
     * @param PayrollRepositoryInterface $repository
     */
    public function __construct(PayrollRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param EmployeeInterface $employee
     *
     * @return bool
     */
    public function employeeHasPayroll(EmployeeInterface $employee): bool
    {
        return $this->repository->hasPayroll($employee);
    }
}
