<?php

declare(strict_types=1);

namespace KejawenLab\Semart\Skeleton\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Blameable\Traits\BlameableEntity;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\SoftDeleteable\Traits\SoftDeleteableEntity;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use KejawenLab\Semart\Skeleton\Component\Contract\Organization\OrganizationInterface;
use KejawenLab\Semart\Skeleton\Contract\Entity\CodeNameableTrait;
use KejawenLab\Semart\Skeleton\Contract\Entity\PrimaryableTrait;
use KejawenLab\Semart\Skeleton\Query\Searchable;
use KejawenLab\Semart\Skeleton\Query\Sortable;
use KejawenLab\Semart\Skeleton\Validator\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="organisasi", indexes={@ORM\Index(name="organisasi_search_idx", columns={"kode", "nama"})})
 * @ORM\Entity()
 *
 * @Gedmo\SoftDeleteable(fieldName="deletedAt")
 *
 * @Searchable({"code", "name"})
 * @Sortable({"code", "name"})
 *
 * @UniqueEntity(fields={"code"}, repositoryClass="KejawenLab\Semart\Skeleton\Repository\OrganizationRepository")
 *
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class Organization implements OrganizationInterface
{
    use BlameableEntity;
    use CodeNameableTrait;
    use PrimaryableTrait;
    use SoftDeleteableEntity;
    use TimestampableEntity;

    /**
     * @ORM\ManyToOne(targetEntity="KejawenLab\Semart\Skeleton\Entity\Organization", fetch="EAGER")
     * @ORM\JoinColumn(name="induk_id", referencedColumnName="id")
     *
     * @Groups({"read"})
     **/
    private $parent;

    /**
     * @ORM\Column(name="level", type="smallint")
     *
     * @Assert\NotBlank()
     *
     * @Groups({"read"})
     */
    private $level;

    public function __construct()
    {
        $this->level = OrganizationInterface::LEVEL_DIVISION;
    }

    public function getParent(): ?OrganizationInterface
    {
        return $this->parent;
    }

    public function setParent(?OrganizationInterface $parent): void
    {
        $this->parent = $parent;
    }

    public function getLevel(): int
    {
        return $this->level;
    }

    public function setLevel(int $level = OrganizationInterface::LEVEL_DIVISION): void
    {
        $this->level = $level;
    }
}
