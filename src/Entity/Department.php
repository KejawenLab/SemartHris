<?php

declare(strict_types=1);

namespace KejawenLab\Application\SemartHris\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Blameable\Traits\BlameableEntity;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\SoftDeleteable\Traits\SoftDeleteableEntity;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use KejawenLab\Application\SemartHris\Component\Company\Model\DepartmentInterface;
use KejawenLab\Application\SemartHris\Util\StringUtil;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity()
 * @ORM\Table(name="departments", indexes={@ORM\Index(name="departments_idx", columns={"code", "name"})})
 *
 * @ApiResource(
 *     attributes={
 *         "filters"={
 *             "code.search",
 *             "name.search"
 *         },
 *         "normalization_context"={"groups"={"read"}},
 *         "denormalization_context"={"groups"={"write"}}
 *     }
 * )
 *
 * @UniqueEntity("code")
 *
 * @Gedmo\SoftDeleteable(fieldName="deletedAt")
 *
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class Department implements DepartmentInterface
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
     * @ORM\ManyToOne(targetEntity="KejawenLab\Application\SemartHris\Entity\Department", fetch="EAGER")
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id")
     *
     * @ApiSubresource()
     *
     * @var DepartmentInterface
     */
    private $parent;

    /**
     * @Groups({"read", "write"})
     *
     * @ORM\Column(type="string", length=7)
     *
     * @Assert\Length(max=7)
     * @Assert\NotBlank()
     *
     * @var string
     */
    private $code;

    /**
     * @Groups({"read", "write"})
     *
     * @ORM\Column(type="string")
     *
     * @Assert\NotBlank()
     *
     * @var string
     */
    private $name;

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
    public function getParent(): ? DepartmentInterface
    {
        return $this->parent;
    }

    /**
     * @param DepartmentInterface|null $parent
     */
    public function setParent(?DepartmentInterface $parent): void
    {
        $this->parent = $parent;
    }

    /**
     * @return string
     */
    public function getCode(): string
    {
        return (string) $this->code;
    }

    /**
     * @param string $code
     */
    public function setCode(string $code): void
    {
        $this->code = StringUtil::uppercase($code);
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return (string) $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = StringUtil::uppercase($name);
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        if ($parent = $this->getParent()) {
            return sprintf('%s -> %s - %s', $parent, $this->getCode(), $this->getName());
        }

        return sprintf('%s - %s', $this->getCode(), $this->getName());
    }
}
