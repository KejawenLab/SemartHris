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
interface CareerHistoryInterface
{
    /**
     * @return string
     */
    public function getId(): string;

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
    public function getCompany(): ? CompanyInterface;

    /**
     * @param CompanyInterface $company
     */
    public function setCompany(?CompanyInterface $company): void;

    /**
     * @return null|DepartmentInterface
     */
    public function getDepartment(): ? DepartmentInterface;

    /**
     * @param DepartmentInterface $department
     */
    public function setDepartment(?DepartmentInterface $department): void;

    /**
     * @return null|JobLevelInterface
     */
    public function getJobLevel(): ? JobLevelInterface;

    /**
     * @param JobLevelInterface $jobLevel
     */
    public function setJobLevel(?JobLevelInterface $jobLevel): void;

    /**
     * @return null|JobTitleInterface
     */
    public function getJobTitle(): ? JobTitleInterface;

    /**
     * @param JobTitleInterface $jobTitle
     */
    public function setJobTitle(?JobTitleInterface $jobTitle): void;

    /**
     * @return null|EmployeeInterface
     */
    public function getSupervisor(): ? EmployeeInterface;

    /**
     * @param EmployeeInterface|null $employee
     */
    public function setSupervisor(?EmployeeInterface $employee): void;

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
    public function getDescription(): ? string;

    /**
     * @param null|string $description
     */
    public function setDescription(?string $description): void;
}
