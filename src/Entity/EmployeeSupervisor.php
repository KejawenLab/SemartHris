<?php

namespace KejawenLab\Application\SemartHris\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Blameable\Traits\BlameableEntity;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\SoftDeleteable\Traits\SoftDeleteableEntity;
use KejawenLab\Application\SemartHris\Component\Employee\Model\EmployeeInterface;
use KejawenLab\Application\SemartHris\Component\Employee\Model\SupervisorInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="employee_supervisors")
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
 * @author Muhamad Surya Iksanudin <surya.iksanudin@kejawenlab.id>
 */
class EmployeeSupervisor implements SupervisorInterface
{
    use BlameableEntity;
    use SoftDeleteableEntity;

    /**
     * @Groups({"read", "write"})
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="UUID")
     * @ORM\Column(type="guid")
     *
     * @var string
     */
    private $id;

    /**
     * @Groups({"write", "read"})
     * @ORM\ManyToOne(targetEntity="KejawenLab\Application\SemartHris\Entity\Employee", fetch="EAGER")
     * @ORM\JoinColumn(name="employee_id", referencedColumnName="id")
     * @Assert\NotBlank()
     * @ApiSubresource()
     *
     * @var EmployeeInterface
     */
    private $employee;

    /**
     * @Groups({"write", "read"})
     * @ORM\ManyToOne(targetEntity="KejawenLab\Application\SemartHris\Entity\Employee", fetch="EAGER")
     * @ORM\JoinColumn(name="supervisor_id", referencedColumnName="id")
     * @Assert\NotBlank()
     * @ApiSubresource()
     *
     * @var EmployeeInterface
     */
    private $supervisor;

    /**
     * @Groups({"read", "write"})
     * @ORM\Column(type="date")
     * @Assert\NotBlank()
     *
     * @var \DateTimeInterface
     */
    private $startDate;

    /**
     * @Groups({"read", "write"})
     * @ORM\Column(type="date", nullable=true)
     *
     * @var \DateTimeInterface|null
     */
    private $endDate;
    /**
     * @Groups({"read", "write"})
     * @ORM\Column(type="boolean")
     *
     * @var bool
     */
    private $active;

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
     * @return EmployeeInterface|null
     */
    public function getSupervisor(): ? EmployeeInterface
    {
        return $this->supervisor;
    }

    /**
     * @param EmployeeInterface|null $supervisor
     */
    public function setSupervisor(EmployeeInterface $supervisor = null): void
    {
        $this->supervisor = $supervisor;
    }

    /**
     * @return \DateTimeInterface
     */
    public function getStartDate(): \DateTimeInterface
    {
        return $this->startDate ?: new \DateTime();
    }

    /**
     * @param \DateTimeInterface $startDate
     */
    public function setStartDate(\DateTimeInterface $startDate): void
    {
        $this->startDate = $startDate;
    }

    /**
     * @return \DateTimeInterface|null
     */
    public function getEndDate(): ? \DateTimeInterface
    {
        return $this->endDate;
    }

    /**
     * @param \DateTimeInterface|null $endDate
     */
    public function setEndDate(\DateTimeInterface $endDate = null): void
    {
        $this->endDate = $endDate;
    }

    /**
     * @return bool
     */
    public function isActive(): bool
    {
        return null === $this->active ? true : $this->active;
    }

    /**
     * @param bool $active
     */
    public function setActive(bool $active): void
    {
        $this->active = $active;
    }
}
