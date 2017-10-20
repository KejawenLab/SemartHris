<?php

namespace KejawenLab\Application\SemartHris\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Blameable\Traits\BlameableEntity;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\SoftDeleteable\Traits\SoftDeleteableEntity;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use KejawenLab\Application\SemartHris\Component\Company\Model\CompanyInterface;
use KejawenLab\Application\SemartHris\Component\Company\Model\DepartmentInterface;
use KejawenLab\Application\SemartHris\Component\Contract\Model\Contractable;
use KejawenLab\Application\SemartHris\Component\Contract\Model\ContractInterface;
use KejawenLab\Application\SemartHris\Component\Contract\Service\ValidateMutaionType;
use KejawenLab\Application\SemartHris\Component\Employee\Model\EmployeeInterface;
use KejawenLab\Application\SemartHris\Component\Job\Model\JobLevelInterface;
use KejawenLab\Application\SemartHris\Component\Job\Model\JobTitleInterface;
use KejawenLab\Application\SemartHris\Component\Job\Model\MutationInterface;
use KejawenLab\Application\SemartHris\Component\Job\MutationType;
use KejawenLab\Application\SemartHris\Validator\Constraint\UniqueContract;
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
 * @UniqueEntity("contract")
 * @UniqueContract()
 *
 * @Gedmo\SoftDeleteable(fieldName="deletedAt")
 *
 * @author Muhamad Surya Iksanudin <surya.iksanudin@kejawenlab.id>
 */
class Mutation implements MutationInterface, Contractable
{
    use BlameableEntity;
    use SoftDeleteableEntity;
    use TimestampableEntity;

    /**
     * @Groups({"read"})
     *
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="UUID")
     * @ORM\Column(type="guid")
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
     * @ApiSubresource()
     *
     * @var EmployeeInterface
     */
    private $employee;

    /**
     * @Groups({"write", "read"})
     *
     * @ORM\ManyToOne(targetEntity="KejawenLab\Application\SemartHris\Entity\Company", fetch="EAGER")
     * @ORM\JoinColumn(name="current_company_id", referencedColumnName="id")
     *
     * @ApiSubresource()
     *
     * @var CompanyInterface
     */
    private $currentCompany;

    /**
     * @Groups({"write", "read"})
     *
     * @ORM\ManyToOne(targetEntity="KejawenLab\Application\SemartHris\Entity\Department", fetch="EAGER")
     * @ORM\JoinColumn(name="current_department_id", referencedColumnName="id")
     *
     * @ApiSubresource()
     *
     * @var DepartmentInterface
     */
    private $currentDepartment;

    /**
     * @Groups({"write", "read"})
     *
     * @ORM\ManyToOne(targetEntity="KejawenLab\Application\SemartHris\Entity\JobLevel", fetch="EAGER")
     * @ORM\JoinColumn(name="current_joblevel_id", referencedColumnName="id")
     *
     * @ApiSubresource()
     *
     * @var JobLevelInterface
     */
    private $currentJobLevel;

    /**
     * @Groups({"write", "read"})
     *
     * @ORM\ManyToOne(targetEntity="KejawenLab\Application\SemartHris\Entity\JobTitle", fetch="EAGER")
     * @ORM\JoinColumn(name="current_jobtitle_id", referencedColumnName="id")
     *
     * @ApiSubresource()
     *
     * @var JobTitleInterface
     */
    private $currentJobTitle;

    /**
     * @Groups({"write", "read"})
     *
     * @ORM\ManyToOne(targetEntity="KejawenLab\Application\SemartHris\Entity\Employee", fetch="EAGER")
     * @ORM\JoinColumn(name="current_supervisor_id", referencedColumnName="id")
     *
     * @ApiSubresource()
     *
     * @var EmployeeInterface
     */
    private $currentSupervisor;

    /**
     * @Groups({"write", "read"})
     *
     * @ORM\ManyToOne(targetEntity="KejawenLab\Application\SemartHris\Entity\Company", fetch="EAGER")
     * @ORM\JoinColumn(name="new_company_id", referencedColumnName="id")
     *
     * @ApiSubresource()
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
     * @ApiSubresource()
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
     * @ApiSubresource()
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
     * @ApiSubresource()
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
     * @ApiSubresource()
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
     * @ApiSubresource()
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
        return ValidateMutaionType::convertToText($this->type);
    }

    /**
     * @see MutationType
     *
     * @param string $type
     */
    public function setType(string $type)
    {
        if (!ValidateMutaionType::isValidType($type)) {
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
    public function setEmployee(EmployeeInterface $employee = null): void
    {
        $this->employee = $employee;
    }

    /**
     * @return CompanyInterface|null
     */
    public function getCurrentCompany(): ? CompanyInterface
    {
        return $this->currentCompany;
    }

    /**
     * @param CompanyInterface|null $currentCompany
     */
    public function setCurrentCompany(CompanyInterface $currentCompany = null): void
    {
        $this->currentCompany = $currentCompany;
    }

    /**
     * @return DepartmentInterface|null
     */
    public function getCurrentDepartment(): ? DepartmentInterface
    {
        return $this->currentDepartment;
    }

    /**
     * @param DepartmentInterface|null $currentDepartment
     */
    public function setCurrentDepartment(DepartmentInterface $currentDepartment = null): void
    {
        $this->currentDepartment = $currentDepartment;
    }

    /**
     * @return JobLevelInterface|null
     */
    public function getCurrentJobLevel(): ? JobLevelInterface
    {
        return $this->currentJobLevel;
    }

    /**
     * @param JobLevelInterface|null $currentJobLevel
     */
    public function setCurrentJobLevel(JobLevelInterface $currentJobLevel = null): void
    {
        $this->currentJobLevel = $currentJobLevel;
    }

    /**
     * @return JobTitleInterface|null
     */
    public function getCurrentJobTitle(): ? JobTitleInterface
    {
        return $this->currentJobTitle;
    }

    /**
     * @param JobTitleInterface|null $currentJobTitle
     */
    public function setCurrentJobTitle(JobTitleInterface $currentJobTitle = null): void
    {
        $this->currentJobTitle = $currentJobTitle;
    }

    /**
     * @return EmployeeInterface|null
     */
    public function getCurrentSupervisor(): ? EmployeeInterface
    {
        return $this->currentSupervisor;
    }

    /**
     * @param EmployeeInterface|null $currentSupervisor
     */
    public function setCurrentSupervisor(EmployeeInterface $currentSupervisor = null): void
    {
        $this->currentSupervisor = $currentSupervisor;
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
    public function setNewCompany(CompanyInterface $newCompany = null): void
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
    public function setNewDepartment(DepartmentInterface $newDepartment = null): void
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
    public function setNewJobLevel(JobLevelInterface $newJobLevel = null): void
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
    public function setNewJobTitle(JobTitleInterface $newJobTitle = null): void
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
    public function setNewSupervisor(EmployeeInterface $newSupervisor = null): void
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
    public function setContract(ContractInterface $contract = null): void
    {
        $this->contract = $contract;
    }

    /**
     * @return array
     */
    public function getTypeChoices(): array
    {
        return ValidateMutaionType::getTypes();
    }
}
