<?php

namespace KejawenLab\Application\SemartHris\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Blameable\Traits\BlameableEntity;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\SoftDeleteable\Traits\SoftDeleteableEntity;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use KejawenLab\Application\SemartHris\Component\Attendance\Model\ShiftmentInterface;
use KejawenLab\Application\SemartHris\Component\Employee\Model\EmployeeInterface;
use KejawenLab\Application\SemartHris\Component\Overtime\Model\OvertimeInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity()
 * @ORM\Table(name="overtimes")
 *
 * @ApiResource(
 *     attributes={
 *         "normalization_context"={"groups"={"read"}},
 *         "denormalization_context"={"groups"={"write"}}
 *     }
 * )
 *
 * @UniqueEntity({"employee", "overtimeDate"})
 *
 * @Gedmo\SoftDeleteable(fieldName="deletedAt")
 *
 * @author Muhamad Surya Iksanudin <surya.iksanudin@kejawenlab.id>
 */
class Overtime implements OvertimeInterface
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
     * @var EmployeeInterface
     */
    private $employee;

    /**
     * @Groups({"read"})
     *
     * @ORM\ManyToOne(targetEntity="KejawenLab\Application\SemartHris\Entity\Shiftment", fetch="EAGER")
     * @ORM\JoinColumn(name="shiftment_id", referencedColumnName="id")
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
    private $overtimeDate;

    /**
     * @Groups({"read", "write"})
     *
     * @ORM\Column(type="time")
     *
     * @Assert\NotBlank()
     *
     * @var \DateTimeInterface
     */
    private $startHour;

    /**
     * @Groups({"read", "write"})
     *
     * @ORM\Column(type="time")
     *
     * @Assert\NotBlank()
     *
     * @var \DateTimeInterface
     */
    private $endHour;

    /**
     * @Groups({"read"})
     *
     * @ORM\Column(type="float", scale=27, precision=2)
     *
     * @var float
     */
    private $calculatedValue;

    /**
     * @Groups({"read", "write"})
     *
     * @ORM\Column(type="boolean")
     *
     * @var bool
     */
    private $holiday;

    /**
     * @Groups({"read"})
     *
     * @ORM\Column(type="boolean")
     *
     * @var bool
     */
    private $overday;

    /**
     * @Groups({"write", "read"})
     *
     * @ORM\ManyToOne(targetEntity="KejawenLab\Application\SemartHris\Entity\Employee", fetch="EAGER")
     * @ORM\JoinColumn(name="approved_by_id", referencedColumnName="id")
     *
     * @var EmployeeInterface
     */
    private $approvedBy;

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
        $this->calculatedValue = (float) 0;
        $this->holiday = false;
        $this->overday = false;
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
    public function getOvertimeDate(): ? \DateTimeInterface
    {
        return $this->overtimeDate;
    }

    /**
     * @param \DateTimeInterface|null $overtimeDate
     */
    public function setOvertimeDate(\DateTimeInterface $overtimeDate = null): void
    {
        $this->overtimeDate = $overtimeDate;
    }

    /**
     * @return \DateTimeInterface|null
     */
    public function getStartHour(): ? \DateTimeInterface
    {
        return $this->startHour;
    }

    /**
     * @param \DateTimeInterface|null $startHour
     */
    public function setStartHour(\DateTimeInterface $startHour = null): void
    {
        $this->startHour = $startHour;
    }

    /**
     * @return \DateTimeInterface|null
     */
    public function getEndHour(): ? \DateTimeInterface
    {
        return $this->endHour;
    }

    /**
     * @param \DateTimeInterface|null $endHour
     */
    public function setEndHour(\DateTimeInterface $endHour = null): void
    {
        $this->endHour = $endHour;
    }

    /**
     * @return float|null
     */
    public function getCalculatedValue(): ? float
    {
        return (float) $this->calculatedValue ?: 0;
    }

    /**
     * @param float $calculatedValue
     */
    public function setCalculatedValue(float $calculatedValue): void
    {
        $this->calculatedValue = $calculatedValue;
    }

    /**
     * @return bool
     */
    public function isHoliday(): bool
    {
        return $this->holiday;
    }

    /**
     * @return bool
     */
    public function getHoliday(): bool
    {
        return $this->isHoliday();
    }

    /**
     * @param bool $holiday
     */
    public function setHoliday(bool $holiday): void
    {
        $this->holiday = $holiday;
    }

    /**
     * @return bool
     */
    public function isOverday(): bool
    {
        return $this->overday;
    }

    public function getOverday(): bool
    {
        return $this->isOverday();
    }

    /**
     * @param bool $overday
     */
    public function setOverday(bool $overday): void
    {
        $this->overday = $overday;
    }

    /**
     * @return bool
     */
    public function isApproved(): bool
    {
        return $this->approvedBy ? true : false;
    }

    /**
     * @return EmployeeInterface|null
     */
    public function getApprovedBy(): ? EmployeeInterface
    {
        return $this->approvedBy;
    }

    /**
     * @param EmployeeInterface|null $approvedBy
     */
    public function setApprovedBy(EmployeeInterface $approvedBy = null): void
    {
        $this->approvedBy = $approvedBy;
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
}
