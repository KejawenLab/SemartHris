<?php

namespace KejawenLab\Application\SemarHris\Component\Company\Model;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@kejawenlab.com>
 */
interface CompanyDepartmentInterface
{
    /**
     * @return string
     */
    public function getId(): string;

    /**
     * @return null|CompanyInterface
     */
    public function getCompany(): ? CompanyInterface;

    /**
     * @param CompanyInterface|null $company
     */
    public function setCompany(CompanyInterface $company = null): void;

    /**
     * @return null|DepartmentInterface
     */
    public function getDepartment(): ? DepartmentInterface;

    /**
     * @param DepartmentInterface|null $department
     */
    public function setDepartment(DepartmentInterface $department = null): void;
}
