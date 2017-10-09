<?php

namespace Persona\Hris\Component\Company\Model;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@personahris.com>
 */
interface DepartmentInterface
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
     * @return string
     */
    public function getCode(): string;

    /**
     * @return string
     */
    public function getName(): string;
}
