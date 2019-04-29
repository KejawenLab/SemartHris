<?php

declare(strict_types=1);

namespace KejawenLab\Application\SemartHris\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Blameable\Traits\BlameableEntity;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\SoftDeleteable\Traits\SoftDeleteableEntity;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use KejawenLab\Application\SemartHris\Component\Salary\Model\ComponentInterface;
use KejawenLab\Application\SemartHris\Component\Salary\Model\PayrollDetailInterface;
use KejawenLab\Application\SemartHris\Component\Salary\Model\PayrollInterface;
use KejawenLab\Application\SemartHris\Configuration\Encrypt;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity()
 * @ORM\Table(name="payroll_details")
 *
 * @ApiResource(
 *     attributes={
 *         "normalization_context"={"groups"={"read"}},
 *         "denormalization_context"={"groups"={"write"}}
 *     }
 * )
 *
 * @Gedmo\SoftDeleteable(fieldName="deletedAt")
 *
 * @Encrypt(properties="benefitValue", keyStore="benefitKey")
 *
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class PayrollDetail implements PayrollDetailInterface
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
     * @ORM\ManyToOne(targetEntity="KejawenLab\Application\SemartHris\Entity\Payroll", fetch="EAGER")
     * @ORM\JoinColumn(name="payroll_id", referencedColumnName="id")
     *
     * @Assert\NotBlank()
     *
     * @var PayrollInterface
     */
    private $payroll;

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
     * @ORM\Column(type="text")
     *
     * @Assert\NotBlank()
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
     * @return PayrollInterface|null
     */
    public function getPayroll(): ? PayrollInterface
    {
        return $this->payroll;
    }

    /**
     * @param PayrollInterface|null $payroll
     */
    public function setPayroll(?PayrollInterface $payroll): void
    {
        $this->payroll = $payroll;
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
}
