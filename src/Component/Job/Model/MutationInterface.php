<?php

declare(strict_types=1);

namespace KejawenLab\Application\SemartHris\Component\Job\Model;

use KejawenLab\Application\SemartHris\Component\Company\Model\CompanyInterface;
use KejawenLab\Application\SemartHris\Component\Company\Model\DepartmentInterface;
use KejawenLab\Application\SemartHris\Component\Contract\Model\ContractInterface;
use KejawenLab\Application\SemartHris\Component\Employee\Model\EmployeeInterface;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
interface MutationInterface
{
    /**
     * @return string
     */
    public function getId(): string;

    /**
     * @return string
     *
     * @see MutationType
     */
    public function getType(): string;

    /**
     * @return null|EmployeeInterface
     */
    public function getEmployee(): ? EmployeeInterface;

    /**
     * @param EmployeeInterface|null $employee
     */
    public function setEmployee(?EmployeeInterface $employee): void;

    /**
     * @return null|CompanyInterface
     */
    public function getOldCompany(): ? CompanyInterface;

    /**
     * @param CompanyInterface|null $company
     */
    public function setOldCompany(?CompanyInterface $company): void;

    /**
     * @return null|CompanyInterface
     */
    public function getNewCompany(): ? CompanyInterface;

    /**
     * @param CompanyInterface|null $company
     */
    public function setNewCompany(?CompanyInterface $company): void;

    /**
     * @return null|DepartmentInterface
     */
    public function getOldDepartment(): ? DepartmentInterface;

    /**
     * @param DepartmentInterface|null $department
     */
    public function setOldDepartment(?DepartmentInterface $department): void;

    /**
     * @return null|DepartmentInterface
     */
    public function getNewDepartment(): ? DepartmentInterface;

    /**
     * @param DepartmentInterface|null $department
     */
    public function setNewDepartment(?DepartmentInterface $department): void;

    /**
     * @return null|EmployeeInterface
     */
    public function getOldSupervisor(): ? EmployeeInterface;

    /**
     * @param EmployeeInterface|null $employee
     */
    public function setOldSupervisor(?EmployeeInterface $employee): void;

    /**
     * @return null|EmployeeInterface
     */
    public function getNewSupervisor(): ? EmployeeInterface;

    /**
     * @param EmployeeInterface|null $employee
     */
    public function setNewSupervisor(?EmployeeInterface $employee): void;

    /**
     * @return null|JobLevelInterface
     */
    public function getOldJobLevel(): ? JobLevelInterface;

    /**
     * @param JobLevelInterface|null $jobLevel
     */
    public function setOldJobLevel(?JobLevelInterface $jobLevel): void;

    /**
     * @return null|JobLevelInterface
     */
    public function getNewJobLevel(): ? JobLevelInterface;

    /**
     * @param JobLevelInterface|null $jobLevel
     */
    public function setNewJobLevel(?JobLevelInterface $jobLevel): void;

    /**
     * @return null|JobTitleInterface
     */
    public function getOldJobTitle(): ? JobTitleInterface;

    /**
     * @param JobTitleInterface|null $jobTitle
     */
    public function setOldJobTitle(?JobTitleInterface $jobTitle): void;

    /**
     * @return null|JobTitleInterface
     */
    public function getNewJobTitle(): ? JobTitleInterface;

    /**
     * @param JobTitleInterface|null $jobTitle
     */
    public function setNewJobTitle(?JobTitleInterface $jobTitle): void;

    /**
     * @return ContractInterface|null
     */
    public function getContract(): ? ContractInterface;

    /**
     * @param ContractInterface|null $contract
     */
    public function setContract(?ContractInterface $contract): void;
}
