<?php

namespace KejawenLab\Application\SemartHris\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use Doctrine\ORM\Mapping as ORM;
use KejawenLab\Application\SemartHris\Component\Address\Model\CityInterface;
use KejawenLab\Application\SemartHris\Component\Address\Model\RegionInterface;
use KejawenLab\Application\SemartHris\Component\Company\Model\CompanyInterface;
use KejawenLab\Application\SemartHris\Component\Company\Model\DepartmentInterface;
use KejawenLab\Application\SemartHris\Component\Employee\Model\EmployeeInterface;
use KejawenLab\Application\SemartHris\Component\Employee\Service\ValidateContractType;
use KejawenLab\Application\SemartHris\Component\Employee\Service\ValidateIdentityType;
use KejawenLab\Application\SemartHris\Component\Employee\Service\ValidateMaritalStatus;
use KejawenLab\Application\SemartHris\Component\Job\Model\JobLevelInterface;
use KejawenLab\Application\SemartHris\Component\Job\Model\JobTitleInterface;
use KejawenLab\Application\SemartHris\Component\Tax\Service\ValidateIndonesiaTaxType;
use KejawenLab\Application\SemartHris\Util\StringUtil;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="employees", indexes={@ORM\Index(name="employees_idx", columns={"code", "full_name"})})
 *
 * @ApiResource(
 *     attributes={
 *         "filters"={
 *             "code.search",
 *             "full_name.search"
 *         },
 *         "normalization_context"={"groups"={"read"}},
 *         "denormalization_context"={"groups"={"write"}}
 *     }
 * )
 *
 * @author Muhamad Surya Iksanudin <surya.iksanudin@kejawenlab.id>
 */
class Employee implements EmployeeInterface
{
    /**
     * @Groups({"read"})
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="UUID")
     * @ORM\Column(type="guid")
     *
     * @var string
     */
    private $id;

    /**
     * @Groups({"read", "write"})
     * @ORM\Column(type="date")
     * @Assert\NotBlank()
     *
     * @var \DateTimeInterface
     */
    private $joinDate;

    /**
     * @Groups({"read", "write"})
     * @ORM\Column(type="string", length=1)
     * @Assert\NotBlank()
     * @Assert\Choice(callback="getEmployeeStatusChoices")
     *
     * @var string
     */
    private $employeeStatus;

    /**
     * @Groups({"read", "write"})
     * @ORM\Column(type="string", length=27)
     * @Assert\NotBlank()
     *
     * @var string
     */
    private $letterNumber;

    /**
     * @Groups({"read", "write"})
     * @ORM\Column(type="date", nullable=true)
     *
     * @var \DateTimeInterface
     */
    private $contractEndDate;

    /**
     * @Groups({"write", "read"})
     * @ORM\ManyToOne(targetEntity="KejawenLab\Application\SemartHris\Entity\Company", fetch="EAGER")
     * @ORM\JoinColumn(name="company_id", referencedColumnName="id")
     * @Assert\NotBlank()
     * @ApiSubresource()
     *
     * @var CompanyInterface
     */
    private $company;

    /**
     * @Groups({"write", "read"})
     * @ORM\ManyToOne(targetEntity="KejawenLab\Application\SemartHris\Entity\Department", fetch="EAGER")
     * @ORM\JoinColumn(name="department_id", referencedColumnName="id")
     * @Assert\NotBlank()
     * @ApiSubresource()
     *
     * @var DepartmentInterface
     */
    private $department;

    /**
     * @Groups({"write", "read"})
     * @ORM\ManyToOne(targetEntity="KejawenLab\Application\SemartHris\Entity\JobLevel", fetch="EAGER")
     * @ORM\JoinColumn(name="joblevel_id", referencedColumnName="id")
     * @Assert\NotBlank()
     * @ApiSubresource()
     *
     * @var JobLevelInterface
     */
    private $jobLevel;

    /**
     * @Groups({"write", "read"})
     * @ORM\ManyToOne(targetEntity="KejawenLab\Application\SemartHris\Entity\JobTitle", fetch="EAGER")
     * @ORM\JoinColumn(name="jobtitle_id", referencedColumnName="id")
     * @Assert\NotBlank()
     * @ApiSubresource()
     *
     * @var JobTitleInterface
     */
    private $jobTitle;

    /**
     * @Groups({"write", "read"})
     * @ORM\ManyToOne(targetEntity="KejawenLab\Application\SemartHris\Entity\Employee", fetch="EAGER")
     * @ORM\JoinColumn(name="supervisor_id", referencedColumnName="id")
     * @Assert\NotBlank()
     * @ApiSubresource()
     *
     * @var EmployeeInterface
     */
    private $supervisor;

    /**
     * @Groups({"read", "write"})
     * @ORM\Column(type="string", length=17)
     * @Assert\NotBlank()
     *
     * @var string
     */
    private $code;

    /**
     * @Groups({"read", "write"})
     * @ORM\Column(type="string")
     * @Assert\NotBlank()
     *
     * @var string
     */
    private $fullName;

    /**
     * @Groups({"write", "read"})
     * @ORM\ManyToOne(targetEntity="KejawenLab\Application\SemartHris\Entity\Region", fetch="EAGER")
     * @ORM\JoinColumn(name="region_of_birth_id", referencedColumnName="id")
     * @Assert\NotBlank()
     * @ApiSubresource()
     *
     * @var RegionInterface
     */
    private $regionOfBirth;

    /**
     * @Groups({"write", "read"})
     * @ORM\ManyToOne(targetEntity="KejawenLab\Application\SemartHris\Entity\City", fetch="EAGER")
     * @ORM\JoinColumn(name="city_of_birth_id", referencedColumnName="id")
     * @Assert\NotBlank()
     * @ApiSubresource()
     *
     * @var CityInterface
     */
    private $cityOfBirth;

    /**
     * @Groups({"read", "write"})
     * @ORM\Column(type="date")
     * @Assert\NotBlank()
     *
     * @var \DateTimeInterface
     */
    private $dateOfBirth;

    /**
     * @Groups({"read", "write"})
     * @ORM\Column(type="string", length=27)
     * @Assert\NotBlank()
     *
     * @var string
     */
    private $identityNumber;

    /**
     * @Groups({"read", "write"})
     * @ORM\Column(type="string")
     * @Assert\NotBlank()
     * @Assert\Choice(callback="getIdentityTypeChoices")
     *
     * @var string
     */
    private $identityType;

    /**
     * @Groups({"read", "write"})
     * @ORM\Column(type="string", length=1)
     * @Assert\NotBlank()
     * @Assert\Choice(callback="getMaritalStatusChoices")
     *
     * @var string
     */
    private $maritalStatus;

    /**
     * @Groups({"read", "write"})
     * @ORM\Column(type="string")
     * @Assert\NotBlank()
     *
     * @var string
     */
    private $email;

    /**
     * @Groups({"read"})
     * @ORM\Column(type="float", scale=27, precision=2, nullable=true)
     *
     * @var float
     */
    private $basicSalary;

    /**
     * @Groups({"read"})
     * @ORM\Column(type="integer", nullable=true)
     *
     * @var int
     */
    private $leaveBalance;

    /**
     * @Groups({"read", "write"})
     * @ORM\Column(type="string")
     * @Assert\NotBlank()
     *
     * @var string
     */
    private $taxGroup;

    /**
     * @Groups({"read"})
     * @ORM\Column(type="date", nullable=true)
     *
     * @var \DateTimeInterface
     */
    private $resignDate;

    /**
     * @Groups({"read", "write"})
     * @ORM\Column(type="boolean")
     *
     * @var bool
     */
    private $haveOvertimeBenefit;

    public function __construct()
    {
        $this->haveOvertimeBenefit = false;
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return (string) $this->id;
    }

    /**
     * @return \DateTimeInterface
     */
    public function getJoinDate(): ? \DateTimeInterface
    {
        return $this->joinDate;
    }

    /**
     * @param \DateTimeInterface $joinDate
     */
    public function setJoinDate(\DateTimeInterface $joinDate)
    {
        $this->joinDate = $joinDate;
    }

    /**
     * @return string
     */
    public function getEmployeeStatus(): string
    {
        return (string) $this->employeeStatus;
    }

    /**
     * @param string $employeeStatus
     */
    public function setEmployeeStatus(string $employeeStatus): void
    {
        $this->employeeStatus = $employeeStatus;
    }

    /**
     * @return string
     */
    public function getLetterNumber(): string
    {
        return (string) $this->letterNumber;
    }

    /**
     * @param string $letterNumber
     */
    public function setLetterNumber(string $letterNumber): void
    {
        $this->letterNumber = $letterNumber;
    }

    /**
     * @return \DateTimeInterface
     */
    public function getContractEndDate(): ? \DateTimeInterface
    {
        return $this->contractEndDate;
    }

    /**
     * @param \DateTimeInterface $contractEndDate
     */
    public function setContractEndDate(\DateTimeInterface $contractEndDate): void
    {
        $this->contractEndDate = $contractEndDate;
    }

    /**
     * @return CompanyInterface|null
     */
    public function getCompany(): ? CompanyInterface
    {
        return $this->company;
    }

    /**
     * @param CompanyInterface|null $company
     */
    public function setCompany(CompanyInterface $company = null): void
    {
        $this->company = $company;
    }

    /**
     * @return DepartmentInterface|null
     */
    public function getDepartment(): ? DepartmentInterface
    {
        return $this->department;
    }

    /**
     * @param DepartmentInterface|null $department
     */
    public function setDepartment(DepartmentInterface $department = null): void
    {
        $this->department = $department;
    }

    /**
     * @return JobLevelInterface|null
     */
    public function getJobLevel(): ? JobLevelInterface
    {
        return $this->jobLevel;
    }

    /**
     * @param JobLevelInterface|null $jobLevel
     */
    public function setJobLevel(JobLevelInterface $jobLevel = null): void
    {
        $this->jobLevel = $jobLevel;
    }

    /**
     * @return JobTitleInterface|null
     */
    public function getJobTitle(): ? JobTitleInterface
    {
        return $this->jobTitle;
    }

    /**
     * @param JobTitleInterface|null $jobTitle
     */
    public function setJobTitle(JobTitleInterface $jobTitle = null): void
    {
        $this->jobTitle = $jobTitle;
    }

    /**
     * @return EmployeeInterface|null
     */
    public function getSupervisor(): ? EmployeeInterface
    {
        return $this->supervisor;
    }

    /**
     * @param EmployeeInterface|null $supervisor
     */
    public function setSupervisor(EmployeeInterface $supervisor = null): void
    {
        $this->supervisor = $supervisor;
    }

    /**
     * @return string
     */
    public function getCode(): string
    {
        return (string) $this->code;
    }

    /**
     * @param string $code
     */
    public function setCode(string $code): void
    {
        $this->code = StringUtil::uppercase($code);
    }

    /**
     * @return string
     */
    public function getFullName(): string
    {
        return (string) $this->fullName;
    }

    /**
     * @param mixed $fullName
     */
    public function setFullName($fullName): void
    {
        $this->fullName = StringUtil::uppercase($fullName);
    }

    /**
     * @return RegionInterface|null
     */
    public function getRegionOfBirth(): ? RegionInterface
    {
        return $this->regionOfBirth;
    }

    /**
     * @param RegionInterface|null $regionOfBirth
     */
    public function setRegionOfBirth(RegionInterface $regionOfBirth = null): void
    {
        $this->regionOfBirth = $regionOfBirth;
    }

    /**
     * @return CityInterface|null
     */
    public function getCityOfBirth(): ? CityInterface
    {
        return $this->cityOfBirth;
    }

    /**
     * @param CityInterface|null $cityOfBirth
     */
    public function setCityOfBirth(CityInterface $cityOfBirth = null): void
    {
        $this->cityOfBirth = $cityOfBirth;
    }

    /**
     * @return \DateTimeInterface
     */
    public function getDateOfBirth(): ? \DateTimeInterface
    {
        return $this->dateOfBirth;
    }

    /**
     * @param \DateTimeInterface $dateOfBirth
     */
    public function setDateOfBirth(\DateTimeInterface $dateOfBirth): void
    {
        $this->dateOfBirth = $dateOfBirth;
    }

    /**
     * @return string
     */
    public function getIdentityNumber(): string
    {
        return (string) $this->identityNumber;
    }

    /**
     * @param string $identityNumber
     */
    public function setIdentityNumber(string $identityNumber): void
    {
        $this->identityNumber = $identityNumber;
    }

    /**
     * @return string
     */
    public function getIdentityType(): string
    {
        return (string) $this->identityType;
    }

    /**
     * @param string $identityType
     */
    public function setIdentityType(string $identityType): void
    {
        $this->identityType = $identityType;
    }

    /**
     * @return string
     */
    public function getMaritalStatus(): string
    {
        return (string) $this->maritalStatus;
    }

    /**
     * @param string $maritalStatus
     */
    public function setMaritalStatus(string $maritalStatus): void
    {
        $this->maritalStatus = $maritalStatus;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return (string) $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    /**
     * @return float
     */
    public function getBasicSalary(): float
    {
        return (float) $this->basicSalary;
    }

    /**
     * @param float $basicSalary
     */
    public function setBasicSalary(float $basicSalary): void
    {
        $this->basicSalary = $basicSalary;
    }

    /**
     * @return int
     */
    public function getLeaveBalance(): int
    {
        return (int) $this->leaveBalance;
    }

    /**
     * @param int $leaveBalance
     */
    public function setLeaveBalance(int $leaveBalance): void
    {
        $this->leaveBalance = $leaveBalance;
    }

    /**
     * @return string
     */
    public function getTaxGroup(): string
    {
        return (string) $this->taxGroup;
    }

    /**
     * @param string $taxGroup
     */
    public function setTaxGroup(string $taxGroup): void
    {
        $this->taxGroup = $taxGroup;
    }

    /**
     * @return \DateTimeInterface
     */
    public function getResignDate(): ? \DateTimeInterface
    {
        return $this->resignDate;
    }

    /**
     * @param \DateTimeInterface $resignDate
     */
    public function setResignDate(\DateTimeInterface $resignDate): void
    {
        $this->resignDate = $resignDate;
    }

    /**
     * @return bool
     */
    public function isHaveOvertimeBenefit(): bool
    {
        return $this->haveOvertimeBenefit;
    }

    /**
     * @param bool $haveOvertimeBenefit
     */
    public function setHaveOvertimeBenefit(bool $haveOvertimeBenefit): void
    {
        $this->haveOvertimeBenefit = $haveOvertimeBenefit;
    }

    /**
     * @return bool
     */
    public function isResign(): bool
    {
        $now = new \DateTime();
        if (!$this->getResignDate()) {
            return false;
        }

        if ($now > $this->getResignDate()) {
            return false;
        }

        return true;
    }

    /**
     * @return array
     */
    public function getEmployeeStatusChoices(): array
    {
        return ValidateContractType::getContractTypes();
    }

    /**
     * @return array
     */
    public function getMaritalStatusChoices(): array
    {
        return ValidateMaritalStatus::getMaritalStatus();
    }

    /**
     * @return array
     */
    public function getIdentityTypeChoices(): array
    {
        return ValidateIdentityType::getIdentityTypes();
    }

    /**
     * @return array
     */
    public function getTaxGroupChoices(): array
    {
        return ValidateIndonesiaTaxType::getTaxGroups();
    }
}
