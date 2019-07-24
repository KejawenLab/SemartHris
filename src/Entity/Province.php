<?php
/**
 * This file is part of the Semart HRIS Application.
 *
 * (c) Muhamad Surya Iksanudin <surya.kejawen@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace KejawenLab\Semart\Skeleton\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Blameable\Traits\BlameableEntity;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\SoftDeleteable\Traits\SoftDeleteableEntity;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use KejawenLab\Semart\Skeleton\Component\Contract\Address\ProvinceInterface;
use KejawenLab\Semart\Skeleton\Contract\Entity\CodeNameableTrait;
use KejawenLab\Semart\Skeleton\Contract\Entity\PrimaryableTrait;
use KejawenLab\Semart\Skeleton\Query\Searchable;
use KejawenLab\Semart\Skeleton\Query\Sortable;
use KejawenLab\Semart\Skeleton\Validator\UniqueEntity;

/**
 * @ORM\Table(name="alamat_propinsi", indexes={@ORM\Index(name="alamat_propinsi_search_idx", columns={"kode", "nama"})})
 * @ORM\Entity()
 *
 * @Gedmo\SoftDeleteable(fieldName="deletedAt")
 *
 * @Searchable({"code", "name"})
 * @Sortable({"code", "name"})
 *
 * @UniqueEntity(fields={"code"}, repositoryClass="KejawenLab\Semart\Skeleton\Repository\ProvinceRepository")
 *
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class Province implements ProvinceInterface
{
    use BlameableEntity;
    use CodeNameableTrait;
    use PrimaryableTrait;
    use SoftDeleteableEntity;
    use TimestampableEntity;
}
