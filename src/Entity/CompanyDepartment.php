<?php

namespace KejawenLab\Application\SemarHris\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use Doctrine\ORM\Mapping as ORM;
use KejawenLab\Application\SemarHris\Component\Company\Model\CompanyDepartmentInterface;
use KejawenLab\Application\SemarHris\Component\Company\Model\CompanyInterface;
use KejawenLab\Application\SemarHris\Component\Company\Model\DepartmentInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="company_departments")
 *
 * @ApiResource(
 *     attributes={
 *         "normalization_context"={"groups"={"read"}},
 *         "denormalization_context"={"groups"={"write"}}
 *     }
 * )
 *
 * @UniqueEntity({"department", "company"})
 *
 * @author Muhamad Surya Iksanudin <surya.iksanudin@kejawenlab.id>
 */
class CompanyDepartment implements CompanyDepartmentInterface
{
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
     * @ORM\ManyToOne(targetEntity="KejawenLab\Application\SemarHris\Entity\Department", fetch="EAGER")
     * @ORM\JoinColumn(name="department_id", referencedColumnName="id")
     * @Assert\NotBlank()
     * @ApiSubresource()
     *
     * @var DepartmentInterface
     */
    private $department;

    /**
     * @Groups({"write", "read"})
     * @ORM\ManyToOne(targetEntity="KejawenLab\Application\SemarHris\Entity\Company", fetch="EAGER")
     * @ORM\JoinColumn(name="company_id", referencedColumnName="id")
     * @
     * @ApiSubresource()
     *
     * @var CompanyInterface
     */
    private $company;

    /**
     * @return string
     */
    public function getId(): string
    {
        return (string) $this->id;
    }

    /**
     * @return null|DepartmentInterface
     */
    public function getDepartment(): ? DepartmentInterface
    {
        return $this->department;
    }

    /**
     * @param DepartmentInterface|null $department
     */
    public function setDepartment(DepartmentInterface $department = null): void
    {
        $this->department = $department;
    }

    /**
     * @return null|CompanyInterface
     */
    public function getCompany(): ? CompanyInterface
    {
        return $this->company;
    }

    /**
     * @param CompanyInterface|null $company
     */
    public function setCompany(CompanyInterface $company = null): void
    {
        $this->company = $company;
    }
}
