<?php

namespace KejawenLab\Application\SemartHris\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use Doctrine\ORM\Mapping as ORM;
use KejawenLab\Application\SemartHris\Component\Employee\Model\EmployeeContractInterface;
use KejawenLab\Application\SemartHris\Component\Employee\Model\EmployeeInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="employee_contracts")
 *
 * @ApiResource(
 *     attributes={
 *         "normalization_context"={"groups"={"read"}},
 *         "denormalization_context"={"groups"={"write"}}
 *     }
 * )
 *
 * @author Muhamad Surya Iksanudin <surya.iksanudin@kejawenlab.id>
 */
class EmployeeContract implements EmployeeContractInterface
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
     * @ORM\ManyToOne(targetEntity="KejawenLab\Application\SemartHris\Entity\Employee", fetch="EAGER")
     * @ORM\JoinColumn(name="employee_id", referencedColumnName="id")
     * @Assert\NotBlank()
     * @ApiSubresource()
     *
     * @var EmployeeInterface
     */
    private $employee;
}
