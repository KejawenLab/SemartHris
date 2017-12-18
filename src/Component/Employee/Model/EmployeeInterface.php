<?php

declare(strict_types=1);

namespace KejawenLab\Application\SemartHris\Component\Employee\Model;

use KejawenLab\Application\SemartHris\Component\Address\Model\Addressable;
use KejawenLab\Application\SemartHris\Component\Address\Model\CityInterface;
use KejawenLab\Application\SemartHris\Component\Address\Model\RegionInterface;
use KejawenLab\Application\SemartHris\Component\Company\Model\CompanyInterface;
use KejawenLab\Application\SemartHris\Component\Company\Model\DepartmentInterface;
use KejawenLab\Application\SemartHris\Component\Employee\ContractType;
use KejawenLab\Application\SemartHris\Component\Employee\Gender;
use KejawenLab\Application\SemartHris\Component\Employee\IdentityType;
use KejawenLab\Application\SemartHris\Component\Employee\MaritalStatus;
use KejawenLab\Application\SemartHris\Component\Employee\RiskRatio;
use KejawenLab\Application\SemartHris\Component\Job\Model\JobLevelInterface;
use KejawenLab\Application\SemartHris\Component\Job\Model\JobTitleInterface;
use KejawenLab\Application\SemartHris\Component\Tax\TaxGroup;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
interface EmployeeInterface extends Addressable
{
    /**
     * @return string
     */
    public function getId(): string;

    /**
     * @return \DateTimeInterface
     */
    public function getJoinDate(): ? \DateTimeInterface;

    /**
     * @param \DateTimeInterface $date
     */
    public function setJoinDate(\DateTimeInterface $date): void;

    /**
     * @return string
     *
     * @see ContractType
     */
    public function getEmployeeStatus(): string;

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
     * @return JobLevelInterface|null
     */
    public function getJobLevel(): ? JobLevelInterface;

    /**
     * @param JobLevelInterface|null $jobLevel
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
     * @return string
     */
    public function getCode(): string;

    /**
     * @return string
     */
    public function getFullName(): string;

    /**
     * @return string
     *
     * @see Gender
     */
    public function getGender(): string;

    /**
     * @return null|RegionInterface
     */
    public function getRegionOfBirth(): ? RegionInterface;

    /**
     * @param RegionInterface $region
     */
    public function setRegionOfBirth(?RegionInterface $region): void;

    /**
     * @return null|CityInterface
     */
    public function getCityOfBirth(): ? CityInterface;

    /**
     * @param CityInterface $city
     */
    public function setCityOfBirth(?CityInterface $city): void;

    /**
     * @return \DateTimeInterface
     */
    public function getDateOfBirth(): ? \DateTimeInterface;

    /**
     * @return string
     */
    public function getIdentityNumber(): string;

    /**
     * @return string
     *
     * @see IdentityType
     */
    public function getIdentityType(): string;

    /**
     * @return string
     *
     * @see MaritalStatus
     */
    public function getMaritalStatus(): string;

    /**
     * @return string
     */
    public function getEmail(): string;

    /**
     * @param bool $haveOvertimeBenefit
     */
    public function setHaveOvertimeBenefit(bool $haveOvertimeBenefit): void;

    /**
     * @return int
     */
    public function getLeaveBalance(): int;

    /**
     * @param int $leaveBalance
     */
    public function setLeaveBalance(int $leaveBalance): void;

    /**
     * @return string
     *
     * @see TaxGroup
     */
    public function getTaxGroup(): string;

    /**
     * @param null|string $taxGroup
     *
     * @see TaxGroup
     */
    public function setTaxGroup(?string $taxGroup): void;

    /**
     * @return bool
     */
    public function isHaveOvertimeBenefit(): bool;

    /**
     * @return string
     *
     * @see RiskRatio
     */
    public function getRiskRatio(): ? string;

    /**
     * @param null|string $riskRatio
     *
     * @see RiskRatio
     */
    public function setRiskRatio(?string $riskRatio): void;

    /**
     * @return bool
     */
    public function isResign(): bool;
}
