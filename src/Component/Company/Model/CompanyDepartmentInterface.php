<?php

declare(strict_types=1);

namespace KejawenLab\Application\SemartHris\Component\Company\Model;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
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
    public function setCompany(?CompanyInterface $company): void;

    /**
     * @return null|DepartmentInterface
     */
    public function getDepartment(): ? DepartmentInterface;

    /**
     * @param DepartmentInterface|null $department
     */
    public function setDepartment(?DepartmentInterface $department): void;
}
