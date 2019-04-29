<?php

declare(strict_types=1);

namespace KejawenLab\Application\SemartHris\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Blameable\Traits\BlameableEntity;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\SoftDeleteable\Traits\SoftDeleteableEntity;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use KejawenLab\Application\SemartHris\Component\Contract\Model\Contractable;
use KejawenLab\Application\SemartHris\Component\Contract\Model\ContractInterface;
use KejawenLab\Application\SemartHris\Component\Employee\Model\EmployeeInterface;
use KejawenLab\Application\SemartHris\Component\Salary\Model\BenefitHistoryInterface;
use KejawenLab\Application\SemartHris\Component\Salary\Model\ComponentInterface;
use KejawenLab\Application\SemartHris\Configuration\Encrypt;
use KejawenLab\Application\SemartHris\Validator\Constraint\UniqueContract;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity()
 * @ORM\Table(name="salary_benefit_histories")
 *
 * @ApiResource(
 *     attributes={
 *         "normalization_context"={"groups"={"read"}},
 *         "denormalization_context"={"groups"={"write"}}
 *     }
 * )
 *
 * @UniqueEntity({"employee", "component"})
 * @UniqueEntity("contract", message="semarthris.contract.already_used")
 * @UniqueContract()
 *
 * @Gedmo\SoftDeleteable(fieldName="deletedAt")
 *
 * @Encrypt(properties={"newBenefitValue", "oldBenefitValue"}, keyStore="benefitKey")
 *
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class SalaryBenefitHistory implements BenefitHistoryInterface, Contractable
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
     * @ORM\ManyToOne(targetEntity="KejawenLab\Application\SemartHris\Entity\SalaryComponent", fetch="EAGER")
     * @ORM\JoinColumn(name="component_id", referencedColumnName="id")
     *
     * @Assert\NotBlank()
     *
     * @var ComponentInterface
     */
    private $component;

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
     *
     * @ORM\Column(type="text", nullable=true)
     *
     * @Assert\NotBlank()
     *
     * @var string
     */
    private $newBenefitValue;

    /**
     * @Groups({"read"})
     *
     * @ORM\Column(type="text", nullable=true)
     *
     * @var string
     */
    private $oldBenefitValue;

    /**
     * @ORM\Column(type="string", nullable=true)
     *
     * @var string
     */
    private $benefitKey;

    /**
     * @Groups({"read", "write"})
     *
     * @ORM\Column(type="string", nullable=true)
     *
     * @var string
     */
    private $description;

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
     * @return ComponentInterface|null
     */
    public function getComponent(): ? ComponentInterface
    {
        return $this->component;
    }

    /**
     * @param ComponentInterface|null $component
     */
    public function setComponent(?ComponentInterface $component): void
    {
        $this->component = $component;
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
     * @return null|string
     */
    public function getNewBenefitValue(): ? string
    {
        return $this->newBenefitValue;
    }

    /**
     * @param null|string $newBenefitValue
     */
    public function setNewBenefitValue(?string $newBenefitValue): void
    {
        $this->newBenefitValue = $newBenefitValue;
    }

    /**
     * @return null|string
     */
    public function getOldBenefitValue(): ? string
    {
        return $this->oldBenefitValue;
    }

    /**
     * @param null|string $oldBenefitValue
     */
    public function setOldBenefitValue(?string $oldBenefitValue): void
    {
        $this->oldBenefitValue = $oldBenefitValue;
    }

    /**
     * @return null|string
     */
    public function getBenefitKey(): ? string
    {
        return $this->benefitKey;
    }

    /**
     * @param null|string $benefitKey
     */
    public function setBenefitKey(?string $benefitKey): void
    {
        $this->benefitKey = $benefitKey;
    }

    /**
     * @return null|string
     */
    public function getDescription(): ? string
    {
        return $this->description;
    }

    /**
     * @param string|null $description
     */
    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }
}
