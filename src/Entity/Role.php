<?php

declare(strict_types=1);

namespace KejawenLab\Semart\Skeleton\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Blameable\Traits\BlameableEntity;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\SoftDeleteable\Traits\SoftDeleteableEntity;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use KejawenLab\Semart\Skeleton\Contract\Entity\PrimaryableTrait;
use KejawenLab\Semart\Skeleton\Query\Searchable;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="semart_hak_akses")
 * @ORM\Entity()
 *
 * @Gedmo\SoftDeleteable(fieldName="deletedAt")
 *
 * @Searchable({"menu.code", "menu.name"})
 *
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class Role
{
    use BlameableEntity;
    use PrimaryableTrait;
    use SoftDeleteableEntity;
    use TimestampableEntity;

    /**
     * @ORM\ManyToOne(targetEntity="KejawenLab\Semart\Skeleton\Entity\Group", fetch="EAGER", cascade={"persist"})
     * @ORM\JoinColumn(name="grup_id", referencedColumnName="id", nullable=false)
     *
     * @Assert\NotBlank()
     *
     * @Groups({"read"})
     **/
    private $group;

    /**
     * @ORM\ManyToOne(targetEntity="KejawenLab\Semart\Skeleton\Entity\Menu", fetch="EAGER", cascade={"persist"})
     * @ORM\JoinColumn(name="menu_id", referencedColumnName="id", nullable=false)
     *
     * @Assert\NotBlank()
     *
     * @Groups({"read"})
     **/
    private $menu;

    /**
     * @ORM\Column(type="boolean")
     *
     * @Groups({"read"})
     */
    private $addable;

    /**
     * @ORM\Column(type="boolean")
     *
     * @Groups({"read"})
     */
    private $editable;

    /**
     * @ORM\Column(type="boolean")
     *
     * @Groups({"read"})
     */
    private $viewable;

    /**
     * @ORM\Column(type="boolean")
     *
     * @Groups({"read"})
     */
    private $deletable;

    public function __construct()
    {
        $this->addable = false;
        $this->editable = false;
        $this->viewable = false;
        $this->deletable = false;
    }

    public function getGroup(): ?Group
    {
        return $this->group;
    }

    public function setGroup(?Group $group): void
    {
        $this->group = $group;
    }

    public function getMenu(): ?Menu
    {
        return $this->menu;
    }

    public function setMenu(?Menu $menu): void
    {
        $this->menu = $menu;
    }

    public function isAddable(): bool
    {
        return $this->addable;
    }

    public function setAddable(bool $addable): void
    {
        $this->addable = (bool) $addable;
    }

    public function isEditable(): bool
    {
        return $this->editable;
    }

    public function setEditable(bool $editable): void
    {
        $this->editable = (bool) $editable;
    }

    public function isViewable(): bool
    {
        return $this->viewable;
    }

    public function setViewable(bool $viewable): void
    {
        $this->viewable = (bool) $viewable;
    }

    public function isDeletable(): bool
    {
        return $this->deletable;
    }

    public function setDeletable(bool $deletable): void
    {
        $this->deletable = (bool) $deletable;
    }
}
