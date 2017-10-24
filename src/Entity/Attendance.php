<?php

namespace KejawenLab\Application\SemartHris\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Blameable\Traits\BlameableEntity;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\SoftDeleteable\Traits\SoftDeleteableEntity;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use KejawenLab\Application\SemartHris\Component\Attendance\Model\AttendanceInterface;
use KejawenLab\Application\SemartHris\Component\Attendance\Model\ShiftmentInterface;
use KejawenLab\Application\SemartHris\Component\Employee\Model\EmployeeInterface;
use KejawenLab\Application\SemartHris\Component\Reason\Model\ReasonInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity()
 * @ORM\Table(name="attendances")
 *
 * @ApiResource(
 *     attributes={
 *         "normalization_context"={"groups"={"read"}},
 *         "denormalization_context"={"groups"={"write"}}
 *     }
 * )
 *
 * @UniqueEntity({"employee", "attendanceDate"})
 *
 * @Gedmo\SoftDeleteable(fieldName="deletedAt")
 *
 * @author Muhamad Surya Iksanudin <surya.iksanudin@kejawenlab.id>
 */
class Attendance implements AttendanceInterface
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
     * @ORM\ManyToOne(targetEntity="KejawenLab\Application\SemartHris\Entity\Shiftment", fetch="EAGER")
     * @ORM\JoinColumn(name="shiftment_id", referencedColumnName="id")
     *
     * @ApiSubresource()
     *
     * @var ShiftmentInterface
     */
    private $shiftment;

    /**
     * @Groups({"read", "write"})
     *
     * @ORM\Column(type="date")
     *
     * @Assert\NotBlank()
     *
     * @var \DateTimeInterface
     */
    private $attendanceDate;

    /**
     * @Groups({"read", "write"})
     *
     * @ORM\Column(type="string", nullable=true)
     *
     * @var string
     */
    private $description;

    /**
     * @Groups({"read", "write"})
     *
     * @ORM\Column(type="time")
     *
     * @Assert\NotBlank()
     *
     * @var \DateTimeInterface
     */
    private $checkIn;

    /**
     * @Groups({"read", "write"})
     *
     * @ORM\Column(type="time")
     *
     * @Assert\NotBlank()
     *
     * @var \DateTimeInterface
     */
    private $checkOut;

    /**
     * @Groups({"read"})
     *
     * @ORM\Column(type="integer")
     *
     * @var int
     */
    private $earlyIn;

    /**
     * @Groups({"read"})
     *
     * @ORM\Column(type="integer")
     *
     * @var int
     */
    private $earlyOut;

    /**
     * @Groups({"read"})
     *
     * @ORM\Column(type="integer")
     *
     * @var int
     */
    private $lateIn;

    /**
     * @Groups({"read"})
     *
     * @ORM\Column(type="integer")
     *
     * @var int
     */
    private $lateOut;

    /**
     * @Groups({"read", "write"})
     *
     * @ORM\Column(type="boolean")
     *
     * @var bool
     */
    private $absent;

    /**
     * @Groups({"write", "read"})
     *
     * @ORM\ManyToOne(targetEntity="KejawenLab\Application\SemartHris\Entity\Reason", fetch="EAGER")
     * @ORM\JoinColumn(name="reason_id", referencedColumnName="id")
     *
     * @ApiSubresource()
     *
     * @var ReasonInterface
     */
    private $reason;

    public function __construct()
    {
        $this->earlyIn = 0;
        $this->earlyOut = 0;
        $this->lateIn = 0;
        $this->lateOut = 0;
        $this->absent = false;
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return (string) $this->id;
    }

    /**
     * @param string $id
     */
    public function setId(string $id)
    {
        $this->id = $id;
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
     * @return ShiftmentInterface|null
     */
    public function getShiftment(): ? ShiftmentInterface
    {
        return $this->shiftment;
    }

    /**
     * @param ShiftmentInterface|null $shiftment
     */
    public function setShiftment(ShiftmentInterface $shiftment = null): void
    {
        $this->shiftment = $shiftment;
    }

    /**
     * @return \DateTimeInterface|null
     */
    public function getAttendanceDate(): ? \DateTimeInterface
    {
        return $this->attendanceDate;
    }

    /**
     * @param \DateTimeInterface|null $attendanceDate
     */
    public function setAttendanceDate(\DateTimeInterface $attendanceDate = null): void
    {
        $this->attendanceDate = $attendanceDate;
    }

    /**
     * @return null|string
     */
    public function getDescription(): ? string
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    /**
     * @return \DateTimeInterface|null
     */
    public function getCheckIn(): \DateTimeInterface
    {
        return $this->checkIn ?: new \DateTime();
    }

    /**
     * @param \DateTimeInterface $checkIn
     */
    public function setCheckIn(\DateTimeInterface $checkIn): void
    {
        $this->checkIn = $checkIn;
    }

    /**
     * @return \DateTimeInterface
     */
    public function getCheckOut(): \DateTimeInterface
    {
        return $this->checkOut ?: new \DateTime();
    }

    /**
     * @param \DateTimeInterface $checkOut
     */
    public function setCheckOut(\DateTimeInterface $checkOut): void
    {
        $this->checkOut = $checkOut;
    }

    /**
     * @return int
     */
    public function getEarlyIn(): int
    {
        return $this->earlyIn;
    }

    /**
     * @param int $earlyIn
     */
    public function setEarlyIn(int $earlyIn): void
    {
        $this->earlyIn = $earlyIn;
    }

    /**
     * @return int
     */
    public function getEarlyOut(): int
    {
        return $this->earlyOut;
    }

    /**
     * @param int $earlyOut
     */
    public function setEarlyOut(int $earlyOut): void
    {
        $this->earlyOut = $earlyOut;
    }

    /**
     * @return int
     */
    public function getLateIn(): int
    {
        return $this->lateIn;
    }

    /**
     * @param int $lateIn
     */
    public function setLateIn(int $lateIn): void
    {
        $this->lateIn = $lateIn;
    }

    /**
     * @return int
     */
    public function getLateOut(): int
    {
        return $this->lateOut;
    }

    /**
     * @param int $lateOut
     */
    public function setLateOut(int $lateOut): void
    {
        $this->lateOut = $lateOut;
    }

    /**
     * @return bool
     */
    public function isAbsent(): bool
    {
        return (bool) $this->absent;
    }

    /**
     * @param bool $absent
     */
    public function setAbsent(bool $absent): void
    {
        $this->absent = $absent;
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
    public function setReason(ReasonInterface $reason = null): void
    {
        $this->reason = $reason;
    }
}
