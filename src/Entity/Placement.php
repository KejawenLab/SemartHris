<?php

declare(strict_types=1);

namespace KejawenLab\Application\SemartHris\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Blameable\Traits\BlameableEntity;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\SoftDeleteable\Traits\SoftDeleteableEntity;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use KejawenLab\Application\SemartHris\Component\Company\Model\CompanyInterface;
use KejawenLab\Application\SemartHris\Component\Company\Model\DepartmentInterface;
use KejawenLab\Application\SemartHris\Component\Contract\Model\Contractable;
use KejawenLab\Application\SemartHris\Component\Contract\Model\ContractInterface;
use KejawenLab\Application\SemartHris\Component\Employee\Model\EmployeeInterface;
use KejawenLab\Application\SemartHris\Component\Job\Model\JobLevelInterface;
use KejawenLab\Application\SemartHris\Component\Job\Model\JobTitleInterface;
use KejawenLab\Application\SemartHris\Component\Job\Model\PlacementInterface;
use KejawenLab\Application\SemartHris\Validator\Constraint\UniqueContract;
use KejawenLab\Application\SemartHris\Validator\Constraint\ValidPlacement;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity()
 * @ORM\Table(name="job_placements")
 *
 * @ApiResource(
 *     attributes={
 *         "normalization_context"={"groups"={"read"}},
 *         "denormalization_context"={"groups"={"write"}}
 *     }
 * )
 *
 * @UniqueEntity("contract", message="semarthris.contract.already_used")
 * @UniqueContract()
 * @ValidPlacement()
 *
 * @Gedmo\SoftDeleteable(fieldName="deletedAt")
 *
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class Placement implements PlacementInterface, Contractable
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
     * @Groups({"write", "read"})
     *
     * @ORM\ManyToOne(targetEntity="KejawenLab\Application\SemartHris\Entity\Employee", fetch="EAGER")
     * @ORM\JoinColumn(name="employee_id", referencedColumnName="id")
     *
     * @Assert\NotBlank()
     *
     * @var EmployeeInterface
     */
    private $employee;

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
     * @var EmployeeInterface
     */
    private $supervisor;

    /**
     * @Groups({"write", "read"})
     *
     * @ORM\ManyToOne(targetEntity="KejawenLab\Application\SemartHris\Entity\Contract", fetch="EAGER")
     * @ORM\JoinColumn(name="contract_id", referencedColumnName="id")
     *
     * @Assert\NotBlank()
     *
     * @var ContractInterface
     */
    private $contract;

    /**
     * @Groups({"read", "write"})
     * @ORM\Column(type="boolean")
     *
     * @var bool
     */
    private $active;

    public function __construct()
    {
        $this->active = true;
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return (string) $this->id;
    }

    /**
     * @return EmployeeInterface|null
     */
    public function getEmployee(): ? EmployeeInterface
    {
        return $this->employee;
    }

    /**
     * @param EmployeeInterface|null $employee
     */
    public function setEmployee(?EmployeeInterface $employee): void
    {
        $this->employee = $employee;
    }

    /**
     * @return CompanyInterface
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
     * @return JobTitleInterface
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
     * @return EmployeeInterface|null
     */
    public function getSupervisor(): ? EmployeeInterface
    {
        return $this->supervisor;
    }

    /**
     * @param EmployeeInterface|null $supervisor
     */
    public function setSupervisor(?EmployeeInterface $supervisor): void
    {
        $this->supervisor = $supervisor;
    }

    /**
     * @return ContractInterface|null
     */
    public function getContract(): ? ContractInterface
    {
        return $this->contract;
    }

    /**
     * @param ContractInterface|null $contract
     */
    public function setContract(?ContractInterface $contract): void
    {
        $this->contract = $contract;
    }

    /**
     * @return bool
     */
    public function isActive(): bool
    {
        return $this->active;
    }

    /**
     * @return bool
     */
    public function getActive(): bool
    {
        return $this->isActive();
    }

    /**
     * @param bool $active
     */
    public function setActive(bool $active)
    {
        $this->active = $active;
    }

    /**
     * @return null|string
     */
    public function getDescription(): ? string
    {
        throw new \RuntimeException();
    }

    /**
     * @param string $description
     */
    public function setDescription(?string $description): void
    {
        throw new \RuntimeException();
    }
}
