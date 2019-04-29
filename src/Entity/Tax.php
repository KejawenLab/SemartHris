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
use KejawenLab\Application\SemartHris\Component\Salary\Model\PayrollPeriodInterface;
use KejawenLab\Application\SemartHris\Component\Tax\Model\TaxInterface;
use KejawenLab\Application\SemartHris\Component\Tax\Service\ValidateTaxGroup;
use KejawenLab\Application\SemartHris\Configuration\Encrypt;
use KejawenLab\Application\SemartHris\Validator\Constraint\ValidSalaryBenefit;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity()
 * @ORM\Table(name="taxs")
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
 * @Encrypt(properties={"untaxable", "taxable", "taxValue"}, keyStore="taxKey")
 *
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class Tax implements TaxInterface
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
     * @Groups({"write"})
     *
     * @ORM\ManyToOne(targetEntity="KejawenLab\Application\SemartHris\Entity\PayrollPeriod", fetch="EAGER")
     * @ORM\JoinColumn(name="period_id", referencedColumnName="id")
     *
     * @Assert\NotBlank()
     *
     * @var PayrollPeriodInterface
     */
    private $period;

    /**
     * @Groups({"write"})
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
     * @ORM\Column(type="text", nullable=true)
     *
     * @var string
     */
    private $untaxable;

    /**
     * @Groups({"read"})
     *
     * @ORM\Column(type="text", nullable=true)
     *
     * @var string
     */
    private $taxable;

    /**
     * @Groups({"read"})
     *
     * @ORM\Column(type="text", nullable=true)
     *
     * @var string
     */
    private $taxValue;

    /**
     * @ORM\Column(type="string", nullable=true)
     *
     * @var string
     */
    private $taxKey;

    /**
     * @return string
     */
    public function getId(): string
    {
        return (string) $this->id;
    }

    /**
     * @return PayrollPeriodInterface
     */
    public function getPeriod(): ? PayrollPeriodInterface
    {
        return $this->period;
    }

    /**
     * @param PayrollPeriodInterface|null $period
     */
    public function setPeriod(?PayrollPeriodInterface $period): void
    {
        $this->period = $period;
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
     * @return null|string
     */
    public function getTaxGroup(): ? string
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
        if (!ValidateTaxGroup::isValidType($taxGroup)) {
            throw new \InvalidArgumentException(sprintf('"%s" is not valid tax type.', $taxGroup));
        }

        $this->taxGroup = $taxGroup;
    }

    /**
     * @return null|string
     */
    public function getUntaxable(): ? string
    {
        return $this->untaxable;
    }

    /**
     * @param null|string $untaxable
     */
    public function setUntaxable(?string $untaxable): void
    {
        $this->untaxable = $untaxable;
    }

    /**
     * @return null|string
     */
    public function getTaxable(): ? string
    {
        return $this->taxable;
    }

    /**
     * @param null|string $taxable
     */
    public function setTaxable(?string $taxable): void
    {
        $this->taxable = $taxable;
    }

    /**
     * @return null|string
     */
    public function getTaxValue(): ? string
    {
        return $this->taxValue;
    }

    /**
     * @param null|string $taxValue
     */
    public function setTaxValue(?string $taxValue): void
    {
        $this->taxValue = $taxValue;
    }

    /**
     * @return null|string
     */
    public function getTaxKey(): ? string
    {
        return $this->taxKey;
    }

    /**
     * @param null|string $taxKey
     */
    public function setTaxKey(?string $taxKey): void
    {
        $this->taxKey = $taxKey;
    }
}
