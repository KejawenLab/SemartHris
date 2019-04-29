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
use KejawenLab\Application\SemartHris\Component\Employee\Service\ValidateRiskRatio;
use KejawenLab\Application\SemartHris\Component\Tax\Model\TaxGroupHistoryInterface;
use KejawenLab\Application\SemartHris\Component\Tax\Service\ValidateTaxGroup;
use KejawenLab\Application\SemartHris\Validator\Constraint\ValidTaxHistory;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity()
 * @ORM\Table(name="tax_group_history")
 *
 * @ApiResource(
 *     attributes={
 *         "normalization_context"={"groups"={"read"}},
 *         "denormalization_context"={"groups"={"write"}}
 *     }
 * )
 *
 * @ValidTaxHistory()
 *
 * @Gedmo\SoftDeleteable(fieldName="deletedAt")
 *
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class TaxGroupHistory implements TaxGroupHistoryInterface
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
     * @Groups({"read"})
     *
     * @ORM\Column(type="string", length=3)
     *
     * @var string
     */
    private $oldTaxGroup;

    /**
     * @Groups({"write", "read"})
     *
     * @ORM\Column(type="string", length=3, nullable=true)
     *
     * @var string
     */
    private $newTaxGroup;

    /**
     * @Groups({"read"})
     *
     * @ORM\Column(type="string", length=3)
     *
     * @var string
     */
    private $oldRiskRatio;

    /**
     * @Groups({"write", "read"})
     *
     * @ORM\Column(type="string", length=3, nullable=true)
     *
     * @var string
     */
    private $newRiskRatio;

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
     * @return null|string
     */
    public function getOldTaxGroup(): ? string
    {
        return $this->oldTaxGroup;
    }

    /**
     * @return null|string
     */
    public function getOldTaxGroupText(): ? string
    {
        return ValidateTaxGroup::convertToText($this->oldTaxGroup);
    }

    /**
     * @param null|string $oldTaxGroup
     */
    public function setOldTaxGroup(?string $oldTaxGroup): void
    {
        if (!ValidateTaxGroup::isValidType($oldTaxGroup)) {
            throw new \InvalidArgumentException(sprintf('"%s" is not valid tax type.', $oldTaxGroup));
        }

        $this->oldTaxGroup = $oldTaxGroup;
    }

    /**
     * @return null|string
     */
    public function getNewTaxGroup(): ? string
    {
        return $this->newTaxGroup;
    }

    /**
     * @return null|string
     */
    public function getNewTaxGroupText(): ? string
    {
        return ValidateTaxGroup::convertToText($this->newTaxGroup);
    }

    /**
     * @param null|string $newTaxGroup
     */
    public function setNewTaxGroup(?string $newTaxGroup): void
    {
        if (!ValidateTaxGroup::isValidType($newTaxGroup)) {
            throw new \InvalidArgumentException(sprintf('"%s" is not valid tax type.', $newTaxGroup));
        }

        $this->newTaxGroup = $newTaxGroup;
    }

    /**
     * @return null|string
     */
    public function getOldRiskRatio(): ? string
    {
        return $this->oldRiskRatio;
    }

    /**
     * @return null|string
     */
    public function getOldRiskRatioText(): ? string
    {
        return ValidateRiskRatio::convertToText($this->oldRiskRatio);
    }

    /**
     * @param null|string $oldRiskRatio
     */
    public function setOldRiskRatio(?string $oldRiskRatio): void
    {
        if (!ValidateRiskRatio::isValidType($oldRiskRatio)) {
            throw new \InvalidArgumentException(sprintf('"%s" is not valid risk ratio.', $oldRiskRatio));
        }

        $this->oldRiskRatio = $oldRiskRatio;
    }

    /**
     * @return null|string
     */
    public function getNewRiskRatio(): ? string
    {
        return $this->newRiskRatio;
    }

    /**
     * @return null|string
     */
    public function getNewRiskRatioText(): ? string
    {
        return ValidateRiskRatio::convertToText($this->newRiskRatio);
    }

    /**
     * @param null|string $newRiskRatio
     */
    public function setNewRiskRatio(?string $newRiskRatio): void
    {
        if (!ValidateRiskRatio::isValidType($newRiskRatio)) {
            throw new \InvalidArgumentException(sprintf('"%s" is not valid risk ratio.', $newRiskRatio));
        }

        $this->newRiskRatio = $newRiskRatio;
    }
}
