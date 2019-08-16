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
use KejawenLab\Semart\Skeleton\Component\Contract\Company\CompanyInterface;
use KejawenLab\Semart\Skeleton\Component\Contract\Company\CompanyLetterInterface;
use KejawenLab\Semart\Skeleton\Component\Contract\Employee\EmployeeInterface;
use KejawenLab\Semart\Skeleton\Contract\Entity\PrimaryableTrait;
use KejawenLab\Semart\Skeleton\Query\Searchable;
use KejawenLab\Semart\Skeleton\Query\Sortable;
use KejawenLab\Semart\Skeleton\Validator\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="perusahaan_surat", indexes={@ORM\Index(name="perusahaan_surat_search_idx", columns={"nomer_surat"})})
 * @ORM\Entity()
 *
 * @Gedmo\SoftDeleteable(fieldName="deletedAt")
 *
 * @Searchable({"letterNumber", "company.name", "subject.fullName"})
 * @Sortable({"letterNumber", "company.name"})
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

    /**
     * @ORM\ManyToOne(targetEntity="KejawenLab\Semart\Skeleton\Entity\Company", fetch="EAGER")
     * @ORM\JoinColumn(name="perusahaan_id", referencedColumnName="id")
     *
     * @Assert\NotBlank()
     *
     * @Groups({"read"})
     **/
    private $company;

    /**
     * @ORM\Column(name="jenis_surat", type="string", length=9)
     *
     * @Assert\NotBlank()
     * @Assert\Length(max=255)
     *
     * @Groups({"read"})
     */
    private $letterType;

    /**
     * @ORM\Column(name="nomer_surat", type="string", length=255)
     *
     * @Assert\NotBlank()
     * @Assert\Length(max=255)
     *
     * @Groups({"read"})
     */
    private $letterNumber;

    /**
     * @ORM\Column(name="judul", type="string", length=77)
     *
     * @Assert\NotBlank()
     * @Assert\Length(max=77)
     *
     * @Groups({"read"})
     */
    private $title;

    /**
     * @ORM\ManyToOne(targetEntity="KejawenLab\Semart\Skeleton\Entity\Company", fetch="EAGER")
     * @ORM\JoinColumn(name="penerima", referencedColumnName="id")
     *
     * @Groups({"read"})
     **/
    private $subject;

    /**
     * @ORM\Column(name="tanggal_berlaku", type="date")
     *
     * @Assert\NotBlank()
     *
     * @Groups({"read"})
     */
    private $dueDate;

    /**
     * @ORM\Column(name="tanggal_akhir", type="date", nullable=true)
     *
     * @Groups({"read"})
     */
    private $endDate;

    public function getCompany(): ?CompanyInterface
    {
        return $this->company;
    }

    public function setCompany(CompanyInterface $company): void
    {
        $this->company = $company;
    }

    public function getLetterType(): ?string
    {
        return $this->letterType;
    }

    public function setLetterType(string $letterType): void
    {
        $this->letterType = $letterType;
    }

    public function getLetterNumber(): ?string
    {
        return $this->letterNumber;
    }

    public function setLetterNumber(string $letterNumber): void
    {
        $this->letterNumber = $letterNumber;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function getSubject(): ?EmployeeInterface
    {
        return $this->subject;
    }

    public function setSubject(EmployeeInterface $subject): void
    {
        $this->subject = $subject;
    }

    public function getDueDate(): ?\DateTime
    {
        return $this->dueDate;
    }

    public function setDueDate(\DateTime $dueDate): void
    {
        $this->dueDate = $dueDate;
    }

    public function getEndDate(): ?\DateTime
    {
        return $this->endDate;
    }

    public function setEndDate(\DateTime $endDate): void
    {
        $this->endDate = $endDate;
    }

    public function isUsed(): bool
    {
        return $this->subject ? true : false;
    }
}
