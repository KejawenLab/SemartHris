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
use KejawenLab\Semart\Skeleton\Component\Contract\Company\CompanyLetterInterface;
use KejawenLab\Semart\Skeleton\Contract\Entity\PrimaryableTrait;
use KejawenLab\Semart\Skeleton\Query\Searchable;
use KejawenLab\Semart\Skeleton\Query\Sortable;
use KejawenLab\Semart\Skeleton\Validator\UniqueEntity;

/**
 * @ORM\Table(name="perusahaan_surat", indexes={@ORM\Index(name="perusahaan_surat_search_idx", columns={"nomer_surat"})})
 * @ORM\Entity()
 *
 * @Gedmo\SoftDeleteable(fieldName="deletedAt")
 *
 * @Searchable({"letterNumber", "subject.fullName"})
 * @Sortable({"letterNumber", "subject.fullName"})
 *
 * @UniqueEntity(fields={"letterNumber"}, repositoryClass="KejawenLab\Semart\Skeleton\Repository\CompanyLetterRepository")
 *
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class CompanyLetter implements CompanyLetterInterface
{
    use BlameableEntity;
    use PrimaryableTrait;
    use SoftDeleteableEntity;
    use TimestampableEntity;

    private $letterType;

    private $letterNumber;

    private $title;

    private $subject;

    private $signedDate;

    private $startDate;

    private $endDate;
}
