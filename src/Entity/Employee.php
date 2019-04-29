<?php

declare(strict_types=1);

namespace KejawenLab\Application\SemartHris\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Blameable\Traits\BlameableEntity;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\SoftDeleteable\Traits\SoftDeleteableEntity;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use KejawenLab\Application\SemartHris\Component\Address\Model\AddressInterface;
use KejawenLab\Application\SemartHris\Component\Address\Model\CityInterface;
use KejawenLab\Application\SemartHris\Component\Address\Model\RegionInterface;
use KejawenLab\Application\SemartHris\Component\Company\Model\CompanyInterface;
use KejawenLab\Application\SemartHris\Component\Company\Model\DepartmentInterface;
use KejawenLab\Application\SemartHris\Component\Contract\Model\Contractable;
use KejawenLab\Application\SemartHris\Component\Contract\Model\ContractInterface;
use KejawenLab\Application\SemartHris\Component\Employee\Model\Superviseable;
use KejawenLab\Application\SemartHris\Component\Employee\RiskRatio;
use KejawenLab\Application\SemartHris\Component\Employee\Service\ValidateContractType;
use KejawenLab\Application\SemartHris\Component\Employee\Service\ValidateGender;
use KejawenLab\Application\SemartHris\Component\Employee\Service\ValidateIdentityType;
use KejawenLab\Application\SemartHris\Component\Employee\Service\ValidateMaritalStatus;
use KejawenLab\Application\SemartHris\Component\Employee\Service\ValidateRiskRatio;
use KejawenLab\Application\SemartHris\Component\Job\Model\JobLevelInterface;
use KejawenLab\Application\SemartHris\Component\Job\Model\JobTitleInterface;
use KejawenLab\Application\SemartHris\Component\Tax\Service\ValidateTaxGroup;
use KejawenLab\Application\SemartHris\Component\User\Model\UserInterface;
use KejawenLab\Application\SemartHris\Util\StringUtil;
use KejawenLab\Application\SemartHris\Validator\Constraint\UniqueContract;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Entity()
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
 * @UniqueEntity("code")
 * @UniqueEntity("identityNumber")
 * @UniqueEntity("email")
 * @UniqueEntity("username")
 * @UniqueEntity("contract", message="semarthris.contract.already_used")
 * @UniqueContract()
 *
 * @Gedmo\SoftDeleteable(fieldName="deletedAt")
 *
 * @Vich\Uploadable()
 *
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class Employee implements Superviseable, Contractable, UserInterface, \Serializable
{
    use BlameableEntity;
    use SoftDeleteableEntity;
    use TimestampableEntity;

    /**
     * @Groups({"read"})
     *
     * @ORM\Id()
     * @ORM\Column(type="uuid", unique=true)
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\CustomIdGenerator(class="Ramsey\Uuid\Doctrine\UuidGenerator")
     *
     * @var string
     */
    private $id;

    /**
     * @Groups({"read"})
     *
     * @ORM\Column(type="date")
     *
     * @var \DateTimeInterface
     */
    private $joinDate;

    /**
     * @Groups({"read", "write"})
     *
     * @ORM\Column(type="string", length=1)
     *
     * @Assert\NotBlank()
     * @Assert\Choice(callback="getEmployeeStatusChoices")
     *
     * @var string
     */
    private $employeeStatus;

    /**
     * @Groups({"write", "read"})
     *
     * @ORM\ManyToOne(targetEntity="KejawenLab\Application\SemartHris\Entity\Contract", fetch="EAGER")
     * @ORM\JoinColumn(name="contract_id", referencedColumnName="id")
     *
     * @Assert\NotBlank()
     *
     * @ApiSubresource()
     *
     * @var ContractInterface
     */
    private $contract;

    /**
     * @Groups({"write", "read"})
     *
     * @ORM\ManyToOne(targetEntity="KejawenLab\Application\SemartHris\Entity\Company", fetch="EAGER")
     * @ORM\JoinColumn(name="company_id", referencedColumnName="id")
     *
     * @Assert\NotBlank()
     *
     * @var CompanyInterface
     */
    private $company;

    /**
     * @Groups({"write", "read"})
     *
     * @ORM\ManyToOne(targetEntity="KejawenLab\Application\SemartHris\Entity\Department", fetch="EAGER")
     * @ORM\JoinColumn(name="department_id", referencedColumnName="id")
     *
     * @Assert\NotBlank()
     *
     * @var DepartmentInterface
     */
    private $department;

    /**
     * @Groups({"write", "read"})
     *
     * @ORM\ManyToOne(targetEntity="KejawenLab\Application\SemartHris\Entity\JobLevel", fetch="EAGER")
     * @ORM\JoinColumn(name="joblevel_id", referencedColumnName="id")
     *
     * @Assert\NotBlank()
     *
     * @var JobLevelInterface
     */
    private $jobLevel;

    /**
     * @Groups({"write", "read"})
     *
     * @ORM\ManyToOne(targetEntity="KejawenLab\Application\SemartHris\Entity\JobTitle", fetch="EAGER")
     * @ORM\JoinColumn(name="jobtitle_id", referencedColumnName="id")
     *
     * @Assert\NotBlank()
     *
     * @var JobTitleInterface
     */
    private $jobTitle;

    /**
     * @Groups({"write", "read"})
     *
     * @ORM\ManyToOne(targetEntity="KejawenLab\Application\SemartHris\Entity\Employee", fetch="EAGER")
     * @ORM\JoinColumn(name="supervisor_id", referencedColumnName="id")
     *
     * @ApiSubresource()
     *
     * @var Superviseable
     */
    private $supervisor;

    /**
     * @Groups({"read", "write"})
     *
     * @ORM\Column(type="string", length=17)
     *
     * @Assert\NotBlank()
     *
     * @var string
     */
    private $code;

    /**
     * @Groups({"read", "write"})
     *
     * @ORM\Column(type="string")
     *
     * @Assert\NotBlank()
     *
     * @var string
     */
    private $fullName;

    /**
     * @Groups({"read", "write"})
     *
     * @ORM\Column(type="string", length=1)
     *
     * @Assert\NotBlank()
     * @Assert\Choice(callback="getGenderChoices")
     *
     * @var string
     */
    private $gender;

    /**
     * @Groups({"write", "read"})
     *
     * @ORM\ManyToOne(targetEntity="KejawenLab\Application\SemartHris\Entity\Region", fetch="EAGER")
     * @ORM\JoinColumn(name="region_of_birth_id", referencedColumnName="id")
     *
     * @Assert\NotBlank()
     *
     * @var RegionInterface
     */
    private $regionOfBirth;

    /**
     * @Groups({"write", "read"})
     *
     * @ORM\ManyToOne(targetEntity="KejawenLab\Application\SemartHris\Entity\City", fetch="EAGER")
     * @ORM\JoinColumn(name="city_of_birth_id", referencedColumnName="id")
     *
     * @Assert\NotBlank()
     *
     * @var CityInterface
     */
    private $cityOfBirth;

    /**
     * @Groups({"read", "write"})
     *
     * @ORM\Column(type="date")
     *
     * @Assert\NotBlank()
     *
     * @var \DateTimeInterface
     */
    private $dateOfBirth;

    /**
     * @Groups({"read", "write"})
     *
     * @ORM\Column(type="string", length=27)
     *
     * @Assert\NotBlank()
     *
     * @var string
     */
    private $identityNumber;

    /**
     * @Groups({"read", "write"})
     *
     * @ORM\Column(type="string", length=1)
     *
     * @Assert\NotBlank()
     * @Assert\Choice(callback="getIdentityTypeChoices")
     *
     * @var string
     */
    private $identityType;

    /**
     * @Groups({"read", "write"})
     *
     * @ORM\Column(type="string", length=1)
     *
     * @Assert\NotBlank()
     * @Assert\Choice(callback="getMaritalStatusChoices")
     *
     * @var string
     */
    private $maritalStatus;

    /**
     * @Groups({"read", "write"})
     *
     * @ORM\Column(type="string")
     *
     * @Assert\NotBlank()
     *
     * @var string
     */
    private $email;

    /**
     * @Groups({"write", "read"})
     *
     * @ORM\OneToOne(targetEntity="KejawenLab\Application\SemartHris\Entity\EmployeeAddress", fetch="EAGER", cascade={"persist"})
     * @ORM\JoinColumn(name="address_id", referencedColumnName="id")
     *
     * @ApiSubresource()
     *
     * @var AddressInterface
     */
    private $address;

    /**
     * @Groups({"read"})
     *
     * @ORM\Column(type="integer", nullable=true)
     *
     * @var int
     */
    private $leaveBalance;

    /**
     * @Groups({"read", "write"})
     *
     * @ORM\Column(type="string", length=3)
     *
     * @Assert\NotBlank()
     *
     * @var string
     */
    private $taxGroup;

    /**
     * @Groups({"read"})
     *
     * @ORM\Column(type="date", nullable=true)
     *
     * @var \DateTimeInterface
     */
    private $resignDate;

    /**
     * @Groups({"read", "write"})
     *
     * @ORM\Column(type="boolean")
     *
     * @var bool
     */
    private $haveOvertimeBenefit;

    /**
     * @Groups({"read", "write"})
     *
     * @ORM\Column(type="string", length=3)
     *
     * @Assert\NotBlank()
     * @Assert\Choice(callback="getRiskRatioChoices")
     *
     * @var string
     */
    private $riskRatio;

    /**
     * @Groups({"read"})
     *
     * @ORM\Column(type="string")
     *
     * @var string
     */
    private $username;

    /**
     * @Groups({"read", "write"})
     *
     * @ORM\Column(type="string")
     *
     * @var string
     */
    private $password;

    /**
     * @Groups({"read", "write"})
     *
     * @ORM\Column(type="array")
     *
     * @var array
     */
    private $roles;

    /**
     * @Vich\UploadableField(mapping="profiles", fileNameProperty="profileImage", size="profileSize")
     *
     * @var File
     */
    private $imageFile;

    /**
     * @ORM\Column(type="string", nullable=true)
     *
     * @var string
     */
    private $profileImage;

    /**
     * @ORM\Column(type="integer", nullable=true)
     *
     * @var int
     */
    private $profileSize;

    /**
     * @var string|null
     */
    private $plainPassword;

    public function __construct()
    {
        $this->haveOvertimeBenefit = false;
        $this->riskRatio = RiskRatio::RISK_VERY_LOW;
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
    public function setJoinDate(\DateTimeInterface $joinDate): void
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
     * @return ContractInterface
     */
    public function getContract(): ? ContractInterface
    {
        return $this->contract;
    }

    /**
     * @param ContractInterface $contract
     */
    public function setContract(?ContractInterface $contract): void
    {
        $this->contract = $contract;
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
    public function setCompany(?CompanyInterface $company): void
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
    public function setDepartment(?DepartmentInterface $department): void
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
    public function setJobLevel(?JobLevelInterface $jobLevel): void
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
    public function setJobTitle(?JobTitleInterface $jobTitle): void
    {
        $this->jobTitle = $jobTitle;
    }

    /**
     * @return Superviseable|null
     */
    public function getSupervisor(): ? Superviseable
    {
        return $this->supervisor;
    }

    /**
     * @param Superviseable|null $supervisor
     */
    public function setSupervisor(?Superviseable $supervisor): void
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
    public function setRegionOfBirth(?RegionInterface $regionOfBirth): void
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
    public function setCityOfBirth(?CityInterface $cityOfBirth): void
    {
        $this->cityOfBirth = $cityOfBirth;
    }

    /**
     * @return \DateTimeInterface
     */
    public function getDateOfBirth(): \DateTimeInterface
    {
        return $this->dateOfBirth ?? new \DateTime();
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
    public function setAddress(?AddressInterface $address): void
    {
        $this->address = $address;
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
        return ValidateTaxGroup::convertToText($this->taxGroup);
    }

    /**
     * @param null|string $taxGroup
     */
    public function setTaxGroup(?string $taxGroup): void
    {
        if (!ValidateTaxGroup::isValidType((string) $taxGroup)) {
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
    public function getRiskRatio(): string
    {
        return $this->riskRatio ?? RiskRatio::RISK_VERY_LOW;
    }

    public function getRiskRatioText(): string
    {
        return ValidateRiskRatio::convertToText($this->riskRatio);
    }

    /**
     * @param null|string $riskRatio
     */
    public function setRiskRatio(?string $riskRatio): void
    {
        if (!ValidateRiskRatio::isValidType((string) $riskRatio)) {
            throw new \InvalidArgumentException(sprintf('"%s" is not valid risk ratio.', $riskRatio));
        }

        $this->riskRatio = $riskRatio;
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
        return $this->roles ?? [self::DEFAULT_ROLE];
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
        $this->roles = [];
        foreach ($roles as $role) {
            $this->addRole($role);
        }
    }

    /**
     * @return bool
     */
    public function isResign(): bool
    {
        $now = \DateTime::createFromFormat('Y-m-d 00:00:00', date('Y-m-d'));
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
        return ValidateTaxGroup::getTypes();
    }

    /**
     * @return array
     */
    public function getRiskRatioChoices(): array
    {
        return ValidateRiskRatio::getTypes();
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
        return null;
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
     * @return null|string
     */
    public function getPlainPassword(): ? string
    {
        return $this->plainPassword;
    }

    /**
     * @param null|string $plainPassword
     */
    public function setPlainPassword(?string $plainPassword): void
    {
        $this->plainPassword = $plainPassword;
    }

    /**
     * @return null|File
     */
    public function getImageFile(): ? File
    {
        return $this->imageFile;
    }

    /**
     * @param File|null $imageFile
     */
    public function setImageFile(?File $imageFile): void
    {
        if ($imageFile) {
            $this->updatedAt = new \DateTime();
        }

        $this->imageFile = $imageFile;
    }

    /**
     * @return string
     */
    public function getProfileImage(): string
    {
        return (string) $this->profileImage;
    }

    /**
     * @param null|string $profileImage
     */
    public function setProfileImage(?string $profileImage): void
    {
        $this->profileImage = $profileImage;
    }

    /**
     * @return int
     */
    public function getProfileSize(): int
    {
        return (int) $this->profileSize;
    }

    /**
     * @param int|null $profileSize
     */
    public function setProfileSize(?int $profileSize): void
    {
        $this->profileSize = $profileSize;
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
}
