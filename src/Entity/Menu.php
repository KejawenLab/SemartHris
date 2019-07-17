<?php

declare(strict_types=1);

namespace KejawenLab\Semart\Skeleton\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Blameable\Traits\BlameableEntity;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\SoftDeleteable\Traits\SoftDeleteableEntity;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use KejawenLab\Semart\Skeleton\Contract\Entity\CodeNameableTrait;
use KejawenLab\Semart\Skeleton\Contract\Entity\PrimaryableTrait;
use KejawenLab\Semart\Skeleton\Query\Searchable;
use KejawenLab\Semart\Skeleton\Query\Sortable;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="semart_menu", indexes={@ORM\Index(name="semart_menu_search_idx", columns={"kode", "nama"})})
 * @ORM\Entity(repositoryClass="KejawenLab\Semart\Skeleton\Repository\MenuRepository")
 *
 * @Gedmo\SoftDeleteable(fieldName="deletedAt")
 *
 * @Searchable({"parent.name", "code", "name"})
 * @Sortable({"parent.name", "code", "name"})
 *
 * @UniqueEntity(fields={"code"}, repositoryMethod="findUniqueBy", message="label.crud.non_unique_or_deleted")
 *
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class Menu
{
    use CodeNameableTrait;
    use BlameableEntity;
    use PrimaryableTrait;
    use SoftDeleteableEntity;
    use TimestampableEntity;

    /**
     * @ORM\ManyToOne(targetEntity="KejawenLab\Semart\Skeleton\Entity\Menu", fetch="EAGER")
     * @ORM\JoinColumn(name="induk_id", referencedColumnName="id")
     *
     * @Groups({"read"})
     **/
    private $parent;

    /**
     * @ORM\Column(name="menu_order", type="integer", nullable=true)
     *
     * @Groups({"read"})
     */
    private $menuOrder;

    /**
     * @ORM\Column(name="icon_class", type="string", length=27, nullable=true)
     *
     * @Assert\Length(max=27)
     *
     * @Groups({"read"})
     */
    private $iconClass;

    /**
     * @ORM\Column(name="nama_rute", type="string", length=77, nullable=true)
     *
     * @Assert\Length(max=77)
     *
     * @Groups({"read"})
     */
    private $routeName;

    /**
     * @ORM\Column(type="boolean")
     *
     * @Groups({"read"})
     */
    private $showable;

    public function __construct()
    {
        $this->menuOrder = 1;
        $this->showable = true;
    }

    public function getParent(): ?self
    {
        return $this->parent;
    }

    public function setParent(?self $parent): void
    {
        $this->parent = $parent;
    }

    public function getMenuOrder(): ?int
    {
        return $this->menuOrder;
    }

    public function setMenuOrder(int $menuOrder): void
    {
        $this->menuOrder = $menuOrder;
    }

    public function getIconClass(): ?string
    {
        return $this->iconClass;
    }

    public function setIconClass(string $iconClass): void
    {
        $this->iconClass = $iconClass;
    }

    public function getRouteName(): ?string
    {
        return $this->routeName;
    }

    public function setRouteName(string $routeName): void
    {
        $this->routeName = $routeName;
    }

    public function isShowable(): bool
    {
        return $this->showable;
    }

    public function setShowable(bool $showable): void
    {
        $this->showable = $showable;
    }
}
