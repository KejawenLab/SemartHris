<?php

namespace KejawenLab\Application\SemartHris\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Blameable\Traits\BlameableEntity;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\SoftDeleteable\Traits\SoftDeleteableEntity;
use KejawenLab\Application\SemartHris\Component\Address\Model\AddressInterface;
use KejawenLab\Application\SemartHris\Component\Address\Model\CityInterface;
use KejawenLab\Application\SemartHris\Component\Address\Model\RegionInterface;
use KejawenLab\Application\SemartHris\Component\Company\Model\CompanyInterface;
use KejawenLab\Application\SemartHris\Component\Company\Model\DepartmentInterface;
use KejawenLab\Application\SemartHris\Component\Employee\Model\EmployeeInterface;
use KejawenLab\Application\SemartHris\Component\Employee\Service\ValidateContractType;
use KejawenLab\Application\SemartHris\Component\Employee\Service\ValidateGender;
use KejawenLab\Application\SemartHris\Component\Employee\Service\ValidateIdentityType;
use KejawenLab\Application\SemartHris\Component\Employee\Service\ValidateMaritalStatus;
use KejawenLab\Application\SemartHris\Component\Job\Model\JobLevelInterface;
use KejawenLab\Application\SemartHris\Component\Job\Model\JobTitleInterface;
use KejawenLab\Application\SemartHris\Component\Security\Model\UserInterface;
use KejawenLab\Application\SemartHris\Component\Tax\Service\ValidateIndonesiaTaxType;
use KejawenLab\Application\SemartHris\Util\StringUtil;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="employees", indexes={@ORM\Index(name="employees_idx", columns={"code", "full_name", "username"})})
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
 * @UniqueEntity("letterNumber")
 * @UniqueEntity("code")
 * @UniqueEntity("identityNumber")
 * @UniqueEntity("email")
 * @UniqueEntity("username")
 *
 * @Gedmo\SoftDeleteable(fieldName="deletedAt")
 *
 * @author Muhamad Surya Iksanudin <surya.iksanudin@kejawenlab.id>
 */
class Employee implements EmployeeInterface, UserInterface, \Serializable
{
    use BlameableEntity;
    use SoftDeleteableEntity;

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
     * @Groups({"read", "write"})
     * @ORM\Column(type="string", length=1)
     * @Assert\NotBlank()
     * @Assert\Choice(callback="getGenderChoices")
     *
     * @var string
     */
    private $gender;

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
     * @ORM\Column(type="string", length=1)
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
     * @Groups({"write", "read"})
     * @ORM\OneToOne(targetEntity="KejawenLab\Application\SemartHris\Entity\EmployeeAddress", fetch="EAGER", cascade={"persist"})
     * @ORM\JoinColumn(name="address_id", referencedColumnName="id")
     * @ApiSubresource()
     *
     * @var AddressInterface
     */
    private $address;

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
     * @ORM\Column(type="string", length=3)
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

    /**
     * @Groups({"read"})
     * @ORM\Column(type="string")
     *
     * @var string
     */
    private $username;

    /**
     * @Groups({"read", "write"})
     * @ORM\Column(type="string")
     *
     * @var string
     */
    private $password;

    /**
     * @Groups({"read", "write"})
     * @ORM\Column(type="array")
     *
     * @var array
     */
    private $roles;

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
     * @return string
     */
    public function getEmployeeStatusText(): string
    {
        return ValidateContractType::convertToText($this->employeeStatus);
    }

    /**
     * @param string $employeeStatus
     */
    public function setEmployeeStatus(string $employeeStatus): void
    {
        if (!ValidateContractType::isValidType($employeeStatus)) {
            throw new \InvalidArgumentException(sprintf('"%s" is not valid contract type.', $employeeStatus));
        }

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
    public function setContractEndDate(\DateTimeInterface $contractEndDate = null): void
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
     * @param string $fullName
     */
    public function setFullName(string $fullName): void
    {
        $this->fullName = StringUtil::uppercase($fullName);
    }

    /**
     * @return string
     */
    public function getGender(): string
    {
        return (string) $this->gender;
    }

    /**
     * @return string
     */
    public function getGenderText(): string
    {
        return ValidateGender::convertToText($this->gender);
    }

    /**
     * @param string $gender
     */
    public function setGender(string $gender)
    {
        if (!ValidateGender::isValidType($gender)) {
            throw new \InvalidArgumentException(sprintf('"%s" is not valid gender.', $gender));
        }

        $this->gender = $gender;
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
    public function getDateOfBirth(): \DateTimeInterface
    {
        return $this->dateOfBirth ?: new \DateTime();
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
     * @return string
     */
    public function getIdentityTypeText(): string
    {
        return ValidateIdentityType::convertToText($this->identityType);
    }

    /**
     * @param string $identityType
     */
    public function setIdentityType(string $identityType): void
    {
        if (!ValidateIdentityType::isValidType($identityType)) {
            throw new \InvalidArgumentException(sprintf('"%s" is not valid identity type.', $identityType));
        }

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
     * @return string
     */
    public function getMaritalStatusText(): string
    {
        return ValidateMaritalStatus::convertToText($this->maritalStatus);
    }

    /**
     * @param string $maritalStatus
     */
    public function setMaritalStatus(string $maritalStatus): void
    {
        if (!ValidateMaritalStatus::isValidType($maritalStatus)) {
            throw new \InvalidArgumentException(sprintf('"%s" is not valid marital status.', $maritalStatus));
        }

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
     * @return AddressInterface|null
     */
    public function getAddress(): ? AddressInterface
    {
        return $this->address;
    }

    /**
     * @param AddressInterface|null $address
     */
    public function setAddress(AddressInterface $address = null): void
    {
        $this->address = $address;
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
     * @return string
     */
    public function getTaxGroupText(): string
    {
        return ValidateIndonesiaTaxType::convertToText($this->taxGroup);
    }

    /**
     * @param string $taxGroup
     */
    public function setTaxGroup(string $taxGroup): void
    {
        if (!ValidateIndonesiaTaxType::isValidType($taxGroup)) {
            throw new \InvalidArgumentException(sprintf('"%s" is not valid tax type.', $taxGroup));
        }

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
     * @see self::isHaveOvertimeBenefit()
     *
     * @return bool
     */
    public function getHaveOvertimeBenefit(): bool
    {
        return $this->isHaveOvertimeBenefit();
    }

    /**
     * @param bool $haveOvertimeBenefit
     */
    public function setHaveOvertimeBenefit(bool $haveOvertimeBenefit): void
    {
        $this->haveOvertimeBenefit = $haveOvertimeBenefit;
    }

    /**
     * @return string
     */
    public function getUsername(): string
    {
        return (string) $this->username;
    }

    /**
     * @param string $username
     */
    public function setUsername(string $username): void
    {
        $this->username = $username;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    /**
     * @param string $password
     */
    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    /**
     * @return array
     */
    public function getRoles(): array
    {
        return $this->roles ?: ['ROLE_USER'];
    }

    /**
     * @param string $role
     */
    public function addRole(string $role): void
    {
        if (false === strpos($role, 'ROLE_')) {
            $role = sprintf('ROLE_%s', $role);
        }

        $this->roles[] = $role;
    }

    /**
     * @param array $roles
     */
    public function setRoles(array $roles): void
    {
        foreach ($roles as $role) {
            $this->addRole($role);
        }
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
     * @see self::isRegion()
     *
     * @return bool
     */
    public function getResign(): bool
    {
        return $this->isResign();
    }

    /**
     * @return array
     */
    public function getEmployeeStatusChoices(): array
    {
        return ValidateContractType::getTypes();
    }

    /**
     * @return array
     */
    public function getGenderChoices(): array
    {
        return ValidateGender::getTypes();
    }

    /**
     * @return array
     */
    public function getMaritalStatusChoices(): array
    {
        return ValidateMaritalStatus::getTypes();
    }

    /**
     * @return array
     */
    public function getIdentityTypeChoices(): array
    {
        return ValidateIdentityType::getTypes();
    }

    /**
     * @return array
     */
    public function getTaxGroupChoices(): array
    {
        return ValidateIndonesiaTaxType::getTypes();
    }

    /**
     * Returns the salt that was originally used to encode the password.
     *
     * This can return null if the password was not encoded using a salt.
     *
     * @return string|null The salt
     */
    public function getSalt(): ? string
    {
        return '';
    }

    /**
     * Removes sensitive data from the user.
     *
     * This is important if, at any given point, sensitive information like
     * the plain-text password is stored on this object.
     */
    public function eraseCredentials(): void
    {
    }

    /**
     * @return string
     */
    public function serialize(): string
    {
        return serialize([
            $this->id,
            $this->username,
            $this->password,
        ]);
    }

    /**
     * @param string $serialized
     */
    public function unserialize($serialized)
    {
        list($this->id, $this->username, $this->password) = unserialize($serialized);
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return sprintf('%s - %s', $this->getCode(), $this->getFullName());
    }

    /**
     * @return string
     */
    public function getAddressClass(): string
    {
        return EmployeeAddress::class;
    }
}
