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
use KejawenLab\Application\SemartHris\Component\Job\Model\CareerHistoryable;
use KejawenLab\Application\SemartHris\Component\Job\Model\JobLevelInterface;
use KejawenLab\Application\SemartHris\Component\Job\Model\JobTitleInterface;
use KejawenLab\Application\SemartHris\Component\Job\Model\MutationInterface;
use KejawenLab\Application\SemartHris\Component\Job\MutationType;
use KejawenLab\Application\SemartHris\Component\Job\Service\ValidateMutationType;
use KejawenLab\Application\SemartHris\Validator\Constraint\UniqueContract;
use KejawenLab\Application\SemartHris\Validator\Constraint\ValidMutation;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity()
 * @ORM\Table(name="job_mutations")
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
 * @ValidMutation()
 *
 * @Gedmo\SoftDeleteable(fieldName="deletedAt")
 *
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class Mutation implements MutationInterface, Contractable, CareerHistoryable
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
     * @Groups({"read", "write"})
     *
     * @ORM\Column(type="string", length=1)
     *
     * @Assert\Length(max=1)
     * @Assert\NotBlank()
     * @Assert\Choice(callback="getTypeChoices")
     *
     * @var string
     */
    private $type;

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
     * @Groups({"read"})
     *
     * @ORM\ManyToOne(targetEntity="KejawenLab\Application\SemartHris\Entity\Company", fetch="EAGER")
     * @ORM\JoinColumn(name="old_company_id", referencedColumnName="id")
     *
     * @var CompanyInterface
     */
    private $oldCompany;

    /**
     * @Groups({"read"})
     *
     * @ORM\ManyToOne(targetEntity="KejawenLab\Application\SemartHris\Entity\Department", fetch="EAGER")
     * @ORM\JoinColumn(name="old_department_id", referencedColumnName="id")
     *
     * @var DepartmentInterface
     */
    private $oldDepartment;

    /**
     * @Groups({"read"})
     *
     * @ORM\ManyToOne(targetEntity="KejawenLab\Application\SemartHris\Entity\JobLevel", fetch="EAGER")
     * @ORM\JoinColumn(name="old_joblevel_id", referencedColumnName="id")
     *
     * @var JobLevelInterface
     */
    private $oldJobLevel;

    /**
     * @Groups({"read"})
     *
     * @ORM\ManyToOne(targetEntity="KejawenLab\Application\SemartHris\Entity\JobTitle", fetch="EAGER")
     * @ORM\JoinColumn(name="old_jobtitle_id", referencedColumnName="id")
     *
     * @var JobTitleInterface
     */
    private $oldJobTitle;

    /**
     * @Groups({"write", "read"})
     *
     * @ORM\ManyToOne(targetEntity="KejawenLab\Application\SemartHris\Entity\Employee", fetch="EAGER")
     * @ORM\JoinColumn(name="old_supervisor_id", referencedColumnName="id")
     *
     * @var EmployeeInterface
     */
    private $oldSupervisor;

    /**
     * @Groups({"write", "read"})
     *
     * @ORM\ManyToOne(targetEntity="KejawenLab\Application\SemartHris\Entity\Company", fetch="EAGER")
     * @ORM\JoinColumn(name="new_company_id", referencedColumnName="id")
     *
     * @var CompanyInterface
     */
    private $newCompany;

    /**
     * @Groups({"write", "read"})
     *
     * @ORM\ManyToOne(targetEntity="KejawenLab\Application\SemartHris\Entity\Department", fetch="EAGER")
     * @ORM\JoinColumn(name="new_department_id", referencedColumnName="id")
     *
     * @var DepartmentInterface
     */
    private $newDepartment;

    /**
     * @Groups({"write", "read"})
     *
     * @ORM\ManyToOne(targetEntity="KejawenLab\Application\SemartHris\Entity\JobLevel", fetch="EAGER")
     * @ORM\JoinColumn(name="new_joblevel_id", referencedColumnName="id")
     *
     * @var JobLevelInterface
     */
    private $newJobLevel;

    /**
     * @Groups({"write", "read"})
     *
     * @ORM\ManyToOne(targetEntity="KejawenLab\Application\SemartHris\Entity\JobTitle", fetch="EAGER")
     * @ORM\JoinColumn(name="new_jobtitle_id", referencedColumnName="id")
     *
     * @var JobTitleInterface
     */
    private $newJobTitle;

    /**
     * @Groups({"write", "read"})
     *
     * @ORM\ManyToOne(targetEntity="KejawenLab\Application\SemartHris\Entity\Employee", fetch="EAGER")
     * @ORM\JoinColumn(name="new_supervisor_id", referencedColumnName="id")
     *
     * @var EmployeeInterface
     */
    private $newSupervisor;

    /**
     * @Groups({"write", "read"})
     *
     * @ORM\ManyToOne(targetEntity="KejawenLab\Application\SemartHris\Entity\Contract", fetch="EAGER")
     * @ORM\JoinColumn(name="contract_id", referencedColumnName="id")
     *
     * @var ContractInterface
     */
    private $contract;

    /**
     * @return string
     */
    public function getId(): string
    {
        return (string) $this->id;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return (string) $this->type;
    }

    /**
     * @return string
     */
    public function getTypeText(): string
    {
        return ValidateMutationType::convertToText($this->type);
    }

    /**
     * @see MutationType
     *
     * @param string $type
     */
    public function setType(string $type)
    {
        if (!ValidateMutationType::isValidType($type)) {
            throw new \InvalidArgumentException(sprintf('"%s" is not valid type.', $type));
        }

        $this->type = $type;
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
     * @return CompanyInterface|null
     */
    public function getOldCompany(): ? CompanyInterface
    {
        return $this->oldCompany;
    }

    /**
     * @param CompanyInterface|null $oldCompany
     */
    public function setOldCompany(?CompanyInterface $oldCompany): void
    {
        $this->oldCompany = $oldCompany;
    }

    /**
     * @return DepartmentInterface|null
     */
    public function getOldDepartment(): ? DepartmentInterface
    {
        return $this->oldDepartment;
    }

    /**
     * @param DepartmentInterface|null $oldDepartment
     */
    public function setOldDepartment(?DepartmentInterface $oldDepartment): void
    {
        $this->oldDepartment = $oldDepartment;
    }

    /**
     * @return JobLevelInterface|null
     */
    public function getOldJobLevel(): ? JobLevelInterface
    {
        return $this->oldJobLevel;
    }

    /**
     * @param JobLevelInterface|null $oldJobLevel
     */
    public function setOldJobLevel(?JobLevelInterface $oldJobLevel): void
    {
        $this->oldJobLevel = $oldJobLevel;
    }

    /**
     * @return JobTitleInterface|null
     */
    public function getOldJobTitle(): ? JobTitleInterface
    {
        return $this->oldJobTitle;
    }

    /**
     * @param JobTitleInterface|null $oldJobTitle
     */
    public function setOldJobTitle(?JobTitleInterface $oldJobTitle): void
    {
        $this->oldJobTitle = $oldJobTitle;
    }

    /**
     * @return EmployeeInterface|null
     */
    public function getOldSupervisor(): ? EmployeeInterface
    {
        return $this->oldSupervisor;
    }

    /**
     * @param EmployeeInterface|null $oldSupervisor
     */
    public function setOldSupervisor(?EmployeeInterface $oldSupervisor): void
    {
        $this->oldSupervisor = $oldSupervisor;
    }

    /**
     * @return CompanyInterface|null
     */
    public function getNewCompany(): ? CompanyInterface
    {
        return $this->newCompany;
    }

    /**
     * @param CompanyInterface|null $newCompany
     */
    public function setNewCompany(?CompanyInterface $newCompany): void
    {
        $this->newCompany = $newCompany;
    }

    /**
     * @return DepartmentInterface|null
     */
    public function getNewDepartment(): ? DepartmentInterface
    {
        return $this->newDepartment;
    }

    /**
     * @param DepartmentInterface|null $newDepartment
     */
    public function setNewDepartment(?DepartmentInterface $newDepartment): void
    {
        $this->newDepartment = $newDepartment;
    }

    /**
     * @return JobLevelInterface|null
     */
    public function getNewJobLevel(): ? JobLevelInterface
    {
        return $this->newJobLevel;
    }

    /**
     * @param JobLevelInterface|null $newJobLevel
     */
    public function setNewJobLevel(?JobLevelInterface $newJobLevel): void
    {
        $this->newJobLevel = $newJobLevel;
    }

    /**
     * @return JobTitleInterface|null
     */
    public function getNewJobTitle(): ? JobTitleInterface
    {
        return $this->newJobTitle;
    }

    /**
     * @param JobTitleInterface|null $newJobTitle
     */
    public function setNewJobTitle(?JobTitleInterface $newJobTitle): void
    {
        $this->newJobTitle = $newJobTitle;
    }

    /**
     * @return EmployeeInterface|null
     */
    public function getNewSupervisor(): ? EmployeeInterface
    {
        return $this->newSupervisor;
    }

    /**
     * @param EmployeeInterface|null $newSupervisor
     */
    public function setNewSupervisor(?EmployeeInterface $newSupervisor): void
    {
        $this->newSupervisor = $newSupervisor;
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
     * @return array
     */
    public function getTypeChoices(): array
    {
        return ValidateMutationType::getTypes();
    }
}
