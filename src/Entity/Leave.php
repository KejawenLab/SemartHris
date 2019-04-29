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
use KejawenLab\Application\SemartHris\Component\Leave\Model\LeaveInterface;
use KejawenLab\Application\SemartHris\Component\Reason\Model\ReasonInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity()
 * @ORM\Table(name="leaves")
 *
 * @ApiResource(
 *     attributes={
 *         "normalization_context"={"groups"={"read"}},
 *         "denormalization_context"={"groups"={"write"}}
 *     }
 * )
 *
 * @UniqueEntity({"employee", "leaveDate"})
 *
 * @Gedmo\SoftDeleteable(fieldName="deletedAt")
 *
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class Leave implements LeaveInterface
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
     * @Groups({"read", "write"})
     *
     * @ORM\Column(type="date")
     *
     * @Assert\NotBlank()
     *
     * @var \DateTimeInterface
     */
    private $leaveDate;

    /**
     * @Groups({"write", "read"})
     *
     * @ORM\ManyToOne(targetEntity="KejawenLab\Application\SemartHris\Entity\Reason", fetch="EAGER")
     * @ORM\JoinColumn(name="reason_id", referencedColumnName="id")
     *
     * @var ReasonInterface
     */
    private $reason;

    /**
     * @Groups({"read", "write"})
     *
     * @ORM\Column(type="smallint")
     *
     * @var int
     */
    private $amount;

    /**
     * @Groups({"read", "write"})
     *
     * @ORM\Column(type="string", nullable=true)
     *
     * @var string
     */
    private $description;

    public function __construct()
    {
        $this->amount = 1;
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
     * @return \DateTimeInterface|null
     */
    public function getLeaveDate(): ? \DateTimeInterface
    {
        return $this->leaveDate;
    }

    /**
     * @param \DateTimeInterface|null $leaveDate
     */
    public function setLeaveDate(?\DateTimeInterface $leaveDate): void
    {
        $this->leaveDate = $leaveDate;
    }

    /**
     * @return ReasonInterface|null
     */
    public function getReason(): ? ReasonInterface
    {
        return $this->reason;
    }

    /**
     * @param ReasonInterface $reason
     */
    public function setReason(?ReasonInterface $reason): void
    {
        $this->reason = $reason;
    }

    /**
     * @return int
     */
    public function getAmount(): int
    {
        return $this->amount;
    }

    /**
     * @param int|null $amount
     */
    public function setAmount(?int $amount): void
    {
        $this->amount = $amount;
    }

    /**
     * @return null|string
     */
    public function getDescription(): ? string
    {
        return $this->description;
    }

    /**
     * @param null|string $description
     */
    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }
}
