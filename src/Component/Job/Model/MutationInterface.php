<?php

namespace KejawenLab\Application\SemarHris\Component\Job\Model;

use KejawenLab\Application\SemarHris\Component\Company\Model\CompanyInterface;
use KejawenLab\Application\SemarHris\Component\Company\Model\DepartmentInterface;
use KejawenLab\Application\SemarHris\Component\Employee\Model\EmployeeInterface;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@personahris.com>
 */
interface MutationInterface
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
    public function setEmployee(EmployeeInterface $employee = null): void;

    /**
     * @return null|CompanyInterface
     */
    public function getCurrentCompany(): ? CompanyInterface;

    /**
     * @param CompanyInterface $company
     */
    public function setCurrentCompany(CompanyInterface $company = null): void;

    /**
     * @return null|CompanyInterface
     */
    public function getNewCompany(): ? CompanyInterface;

    /**
     * @param CompanyInterface $company
     */
    public function setNewCompany(CompanyInterface $company = null): void;

    /**
     * @return null|DepartmentInterface
     */
    public function getCurrentDepartment(): ? DepartmentInterface;

    /**
     * @param DepartmentInterface $department
     */
    public function setCurrentDepartment(DepartmentInterface $department = null): void;

    /**
     * @return null|DepartmentInterface
     */
    public function getNewDepartment(): ? DepartmentInterface;

    /**
     * @param DepartmentInterface $department
     */
    public function setNewDepartment(DepartmentInterface $department = null): void;

    /**
     * @return \DateTime
     */
    public function getStartDate(): \DateTime;

    /**
     * @return string
     */
    public function getLetterNumber(): string;

    /**
     * @return null|string
     */
    public function getDescription(): ? string;
}
