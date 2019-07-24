<?php

declare(strict_types=1);

namespace KejawenLab\Semart\Skeleton\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Blameable\Traits\BlameableEntity;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\SoftDeleteable\Traits\SoftDeleteableEntity;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use KejawenLab\Semart\Skeleton\Component\Contract\Skill\SkillGroupInterface;
use KejawenLab\Semart\Skeleton\Contract\Entity\NameableTrait;
use KejawenLab\Semart\Skeleton\Contract\Entity\PrimaryableTrait;
use KejawenLab\Semart\Skeleton\Query\Searchable;
use KejawenLab\Semart\Skeleton\Query\Sortable;
use KejawenLab\Semart\Skeleton\Validator\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Table(name="keahlian_grup", indexes={@ORM\Index(name="keahlian_grup_search_idx", columns={"nama"})})
 * @ORM\Entity()
 *
 * @Gedmo\SoftDeleteable(fieldName="deletedAt")
 *
 * @Searchable({"parent.name", "name"})
 * @Sortable({"parent.name", "name"})
 *
 * @UniqueEntity(fields={"name"}, repositoryClass="KejawenLab\Semart\Skeleton\Repository\SkillGroupRepository")
 *
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class SkillGroup implements SkillGroupInterface
{
    use BlameableEntity;
    use NameableTrait;
    use PrimaryableTrait;
    use SoftDeleteableEntity;
    use TimestampableEntity;

    /**
     * @ORM\ManyToOne(targetEntity="KejawenLab\Semart\Skeleton\Entity\SkillGroup", fetch="EAGER")
     * @ORM\JoinColumn(name="induk_id", referencedColumnName="id")
     *
     * @Groups({"read"})
     **/
    private $parent;

    public function getParent(): ?SkillGroupInterface
    {
        return $this->parent;
    }

    public function setParent(SkillGroupInterface $parent): void
    {
        $this->parent = $parent;
    }
}