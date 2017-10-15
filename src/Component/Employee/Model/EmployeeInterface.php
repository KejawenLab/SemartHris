<?php

namespace KejawenLab\Application\SemartHris\Component\Employee\Model;

use KejawenLab\Application\SemartHris\Component\Address\Model\CityInterface;
use KejawenLab\Application\SemartHris\Component\Address\Model\RegionInterface;
use KejawenLab\Application\SemartHris\Component\Company\Model\CompanyInterface;
use KejawenLab\Application\SemartHris\Component\Company\Model\DepartmentInterface;
use KejawenLab\Application\SemartHris\Component\Job\Model\JobLevelInterface;
use KejawenLab\Application\SemartHris\Component\Job\Model\JobTitleInterface;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@kejawenlab.com>
 */
interface EmployeeInterface
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
     * @return string
     */
    public function getEmployeeStatus(): string;

    /**
     * @return string
     */
    public function getLetterNumber(): ? string;

    /**
     * @param string $letterNumber
     */
    public function setLetterNumber(string $letterNumber): void;

    /**
     * @return \DateTimeInterface
     */
    public function getContractEndDate(): ? \DateTimeInterface;

    /**
     * @param \DateTimeInterface $dateTime
     */
    public function setContractEndDate(\DateTimeInterface $dateTime = null): void;

    /**
     * @return null|CompanyInterface
     */
    public function getCompany(): ? CompanyInterface;

    /**
     * @param CompanyInterface $company
     */
    public function setCompany(CompanyInterface $company = null): void;

    /**
     * @return null|DepartmentInterface
     */
    public function getDepartment(): ? DepartmentInterface;

    /**
     * @param DepartmentInterface $department
     */
    public function setDepartment(DepartmentInterface $department = null): void;

    /**
     * @return JobLevelInterface|null
     */
    public function getJobLevel(): ? JobLevelInterface;

    /**
     * @param JobLevelInterface|null $jobLevel
     */
    public function setJobLevel(JobLevelInterface $jobLevel = null): void;

    /**
     * @return null|JobTitleInterface
     */
    public function getJobTitle(): ? JobTitleInterface;

    /**
     * @param JobTitleInterface $jobTitle
     */
    public function setJobTitle(JobTitleInterface $jobTitle = null): void;

    /**
     * @return null|EmployeeInterface
     */
    public function getSupervisor(): ? EmployeeInterface;

    /**
     * @param EmployeeInterface|null $employee
     */
    public function setSupervisor(EmployeeInterface $employee = null): void;

    /**
     * @return string
     */
    public function getCode(): string;

    /**
     * @return string
     */
    public function getFullName(): string;

    /**
     * @return null|RegionInterface
     */
    public function getRegionOfBirth(): ? RegionInterface;

    /**
     * @param RegionInterface $region
     */
    public function setRegionOfBirth(RegionInterface $region = null): void;

    /**
     * @return null|CityInterface
     */
    public function getCityOfBirth(): ? CityInterface;

    /**
     * @param CityInterface $city
     */
    public function setCityOfBirth(CityInterface $city = null): void;

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
     */
    public function getIdentityType(): string;

    /**
     * @return string
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
     * @return float
     */
    public function getBasicSalary(): float;

    /**
     * @param float $basicSalary
     */
    public function setBasicSalary(float $basicSalary): void;

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
     */
    public function getTaxGroup(): string;

    /**
     * @return bool
     */
    public function isHaveOvertimeBenefit(): bool;

    /**
     * @return bool
     */
    public function isResign(): bool;
}
