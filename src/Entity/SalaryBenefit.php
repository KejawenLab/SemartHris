<?php

declare(strict_types=1);

namespace KejawenLab\Application\SemartHris\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Blameable\Traits\BlameableEntity;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\SoftDeleteable\Traits\SoftDeleteableEntity;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use KejawenLab\Application\SemartHris\Component\Employee\Model\EmployeeInterface;
use KejawenLab\Application\SemartHris\Component\Salary\Model\BenefitInterface;
use KejawenLab\Application\SemartHris\Component\Salary\Model\ComponentInterface;
use KejawenLab\Application\SemartHris\Configuration\Encrypt;
use KejawenLab\Application\SemartHris\Validator\Constraint\ValidSalaryBenefit;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

/**
 * @ORM\Entity()
 * @ORM\Table(name="salary_benefits")
 *
 * @ApiResource(
 *     attributes={
 *         "normalization_context"={"groups"={"read"}},
 *         "denormalization_context"={"groups"={"write"}}
 *     }
 * )
 *
 * @UniqueEntity({"employee", "component"})
 * @ValidSalaryBenefit()
 *
 * @Gedmo\SoftDeleteable(fieldName="deletedAt")
 *
 * @Encrypt(properties="benefitValue", keyStore="benefitKey")
 *
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class SalaryBenefit implements BenefitInterface
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
     * @Groups({"read", "write"})
     *
     * @ORM\Column(type="text", nullable=true)
     *
     * @var string
     */
    private $benefitValue;

    /**
     * @ORM\Column(type="string", nullable=true)
     *
     * @var string
     */
    private $benefitKey;

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
     * @return null|string
     */
    public function getBenefitValue(): ? string
    {
        return $this->benefitValue;
    }

    /**
     * @param string|null $benefitValue
     */
    public function setBenefitValue(?string $benefitValue): void
    {
        $this->benefitValue = $benefitValue;
    }

    /**
     * @return string
     */
    public function getBenefitKey(): ? string
    {
        return $this->benefitKey;
    }

    /**
     * @param string $benefitKey
     */
    public function setBenefitKey(?string $benefitKey): void
    {
        $this->benefitKey = $benefitKey;
    }

    /**
     * @param ExecutionContextInterface $context
     * @param mixed                     $payload
     *
     * @Assert\Callback()
     */
    public function checkBenefitValue(ExecutionContextInterface $context, $payload)
    {
        if ($this->component && $this->component->isFixed() && !$this->benefitValue) {
            $context
                ->buildViolation('semarthris.field_required')
                ->atPath('benefitValue')
                ->addViolation()
            ;
        }
    }
}
