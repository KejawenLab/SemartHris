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
use KejawenLab\Application\SemartHris\Component\Salary\Model\AttendanceSummaryInterface;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity()
 * @ORM\Table(name="attendance_summaries", indexes={@ORM\Index(name="attendance_summaries_idx", columns={"month", "year"})})
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
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class AttendanceSummary implements AttendanceSummaryInterface
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
     * @var EmployeeInterface
     */
    private $employee;

    /**
     * @Groups({"read", "write"})
     *
     * @ORM\Column(type="smallint")
     *
     * @var int
     */
    private $year;

    /**
     * @Groups({"read", "write"})
     *
     * @ORM\Column(type="smallint")
     *
     * @var int
     */
    private $month;

    /**
     * @Groups({"read", "write"})
     *
     * @ORM\Column(type="integer")
     *
     * @var int
     */
    private $totalWorkday;

    /**
     * @Groups({"read", "write"})
     *
     * @ORM\Column(type="integer")
     *
     * @var int
     */
    private $totalIn;

    /**
     * @Groups({"read", "write"})
     *
     * @ORM\Column(type="integer")
     *
     * @var int
     */
    private $totalLoyality;

    /**
     * @Groups({"read", "write"})
     *
     * @ORM\Column(type="integer")
     *
     * @var int
     */
    private $totalAbsent;

    /**
     * @Groups({"read", "write"})
     *
     * @ORM\Column(type="integer")
     *
     * @var int
     */
    private $totalOvertime;

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
     * @return int
     */
    public function getYear(): int
    {
        return (int) $this->year;
    }

    /**
     * @param int $year
     */
    public function setYear(int $year): void
    {
        $this->year = $year;
    }

    /**
     * @return int
     */
    public function getMonth(): int
    {
        return (int) $this->month;
    }

    /**
     * @param int $month
     */
    public function setMonth(int $month): void
    {
        $this->month = $month;
    }

    /**
     * @return int
     */
    public function getTotalWorkday(): int
    {
        return (int) $this->totalWorkday;
    }

    /**
     * @param int $totalWorkday
     */
    public function setTotalWorkday(int $totalWorkday): void
    {
        $this->totalWorkday = $totalWorkday;
    }

    /**
     * @return int
     */
    public function getTotalIn(): int
    {
        return (int) $this->totalIn;
    }

    /**
     * @param int $totalIn
     */
    public function setTotalIn(int $totalIn): void
    {
        $this->totalIn = $totalIn;
    }

    /**
     * @return int
     */
    public function getTotalLoyality(): int
    {
        return (int) $this->totalLoyality;
    }

    /**
     * @param int $totalLoyality
     */
    public function setTotalLoyality(int $totalLoyality): void
    {
        $this->totalLoyality = $totalLoyality;
    }

    /**
     * @return int
     */
    public function getTotalAbsent(): int
    {
        return (int) $this->totalAbsent;
    }

    /**
     * @param int $totalAbsent
     */
    public function setTotalAbsent(int $totalAbsent): void
    {
        $this->totalAbsent = $totalAbsent;
    }

    /**
     * @return int
     */
    public function getTotalOvertime(): int
    {
        return (int) $this->totalOvertime;
    }

    /**
     * @param int $totalOvertime
     */
    public function setTotalOvertime(int $totalOvertime): void
    {
        $this->totalOvertime = $totalOvertime;
    }
}
