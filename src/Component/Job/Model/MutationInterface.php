<?php

namespace KejawenLab\Application\SemartHris\Component\Job\Model;

use KejawenLab\Application\SemartHris\Component\Company\Model\CompanyInterface;
use KejawenLab\Application\SemartHris\Component\Company\Model\DepartmentInterface;
use KejawenLab\Application\SemartHris\Component\Contract\Model\ContractInterface;
use KejawenLab\Application\SemartHris\Component\Employee\Model\EmployeeInterface;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@kejawenlab.com>
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
     * @return null|EmployeeInterface
     */
    public function getCurrentSupervisor(): ? EmployeeInterface;

    /**
     * @param EmployeeInterface|null $employee
     */
    public function setCurrenctSupervisor(EmployeeInterface $employee = null): void;

    /**
     * @return null|EmployeeInterface
     */
    public function getNewSupervisor(): ? EmployeeInterface;

    /**
     * @param EmployeeInterface|null $employee
     */
    public function setNewSupervisor(EmployeeInterface $employee = null): void;

    /**
     * @return ContractInterface|null
     */
    public function getContract(): ? ContractInterface;

    /**
     * @param ContractInterface|null $contract
     */
    public function setContract(ContractInterface $contract = null): void;
}
