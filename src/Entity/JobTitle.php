<?php

declare(strict_types=1);

namespace KejawenLab\Semart\Skeleton\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Blameable\Traits\BlameableEntity;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\SoftDeleteable\Traits\SoftDeleteableEntity;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use KejawenLab\Semart\Skeleton\Component\Company\Validator\ValidSupervisor;
use KejawenLab\Semart\Skeleton\Component\Contract\Company\JobTitleInterface;
use KejawenLab\Semart\Skeleton\Contract\Entity\CodeNameableTrait;
use KejawenLab\Semart\Skeleton\Contract\Entity\PrimaryableTrait;
use KejawenLab\Semart\Skeleton\Query\Searchable;
use KejawenLab\Semart\Skeleton\Query\Sortable;
use KejawenLab\Semart\Skeleton\Validator\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="jabatan", indexes={@ORM\Index(name="jabatan_search_idx", columns={"kode", "nama"})})
 * @ORM\Entity()
 *
 * @Gedmo\SoftDeleteable(fieldName="deletedAt")
 *
 * @Searchable({"code", "name"})
 * @Sortable({"code", "name"})
 *
 * @ValidSupervisor()
 * @UniqueEntity(fields={"code"}, repositoryClass="KejawenLab\Semart\Skeleton\Repository\JobTitleRepository")
 *
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class JobTitle implements JobTitleInterface
{
    use BlameableEntity;
    use CodeNameableTrait;
    use PrimaryableTrait;
    use SoftDeleteableEntity;
    use TimestampableEntity;

    /**
     * @ORM\ManyToOne(targetEntity="KejawenLab\Semart\Skeleton\Entity\JobTitle", fetch="EAGER")
     * @ORM\JoinColumn(name="atasan_id", referencedColumnName="id")
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
        $this->level = JobTitleInterface::LEVEL_STAFF;
    }

    public function getParent(): ?JobTitleInterface
    {
        return $this->parent;
    }

    public function setParent(?JobTitleInterface $parent): void
    {
        $this->parent = $parent;
    }

    public function getLevel(): int
    {
        return $this->level;
    }

    public function setLevel(int $level = JobTitleInterface::LEVEL_STAFF): void
    {
        $this->level = $level;
    }
}
