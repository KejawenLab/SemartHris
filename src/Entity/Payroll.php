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
use KejawenLab\Application\SemartHris\Component\Salary\Model\PayrollInterface;
use KejawenLab\Application\SemartHris\Component\Salary\Model\PayrollPeriodInterface;
use KejawenLab\Application\SemartHris\Configuration\Encrypt;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity()
 * @ORM\Table(name="payrolls")
 *
 * @ApiResource(
 *     attributes={
 *         "normalization_context"={"groups"={"read"}},
 *         "denormalization_context"={"groups"={"write"}}
 *     }
 * )
 *
 * @UniqueEntity({"employee", "period"})
 *
 * @Gedmo\SoftDeleteable(fieldName="deletedAt")
 *
 * @Encrypt(properties="takeHomePay", keyStore="takeHomePayKey")
 *
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class Payroll implements PayrollInterface
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
     * @ORM\ManyToOne(targetEntity="KejawenLab\Application\SemartHris\Entity\PayrollPeriod", fetch="EAGER")
     * @ORM\JoinColumn(name="period_id", referencedColumnName="id")
     *
     * @Assert\NotBlank()
     *
     * @var PayrollPeriodInterface
     */
    private $period;

    /**
     * @Groups({"read", "write"})
     *
     * @ORM\Column(type="text", nullable=true)
     *
     * @var string
     */
    private $takeHomePay;

    /**
     * @ORM\Column(type="string", nullable=true)
     *
     * @var string
     */
    private $takeHomePayKey;

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
     * @return null|string
     */
    public function getTakeHomePay(): ? string
    {
        return $this->takeHomePay;
    }

    /**
     * @param string $takeHomePay
     */
    public function setTakeHomePay(string $takeHomePay): void
    {
        $this->takeHomePay = $takeHomePay;
    }

    /**
     * @return null|string
     */
    public function getTakeHomePayKey(): ? string
    {
        return $this->takeHomePayKey;
    }

    /**
     * @param string $takeHomePayKey
     */
    public function setTakeHomePayKey(string $takeHomePayKey): void
    {
        $this->takeHomePayKey = $takeHomePayKey;
    }

    public function close(): void
    {
        $this->period->setClosed(true);
    }
}
