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

/**
 * @ORM\Table(name="semart_grup", indexes={@ORM\Index(name="semart_grup_search_idx", columns={"kode", "nama"})})
 * @ORM\Entity(repositoryClass="KejawenLab\Semart\Skeleton\Repository\GroupRepository")
 *
 * @Gedmo\SoftDeleteable(fieldName="deletedAt")
 *
 * @Searchable({"code", "name"})
 * @Sortable({"code", "name"})
 *
 * @UniqueEntity(fields={"code"}, repositoryMethod="findUniqueBy", message="label.crud.non_unique_or_deleted")
 *
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class Group
{
    public const SUPER_ADMINISTRATOR_CODE = 'SPRADM';

    use BlameableEntity;
    use CodeNameableTrait;
    use PrimaryableTrait;
    use SoftDeleteableEntity;
    use TimestampableEntity;
}
