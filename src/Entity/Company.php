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
use KejawenLab\Semart\Skeleton\Component\Contract\Address\DistrictInterface;
use KejawenLab\Semart\Skeleton\Component\Contract\Address\ProvinceInterface;
use KejawenLab\Semart\Skeleton\Component\Contract\Address\SubDistrictInterface;
use KejawenLab\Semart\Skeleton\Component\Contract\Company\CompanyGroupInterface;
use KejawenLab\Semart\Skeleton\Component\Contract\Company\CompanyInterface;
use KejawenLab\Semart\Skeleton\Contract\Entity\CodeNameableTrait;
use KejawenLab\Semart\Skeleton\Contract\Entity\PrimaryableTrait;
use KejawenLab\Semart\Skeleton\Query\Searchable;
use KejawenLab\Semart\Skeleton\Query\Sortable;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="perusahaan", indexes={@ORM\Index(name="perusahaan_search_idx", columns={"kode", "nama"})})
 * @ORM\Entity(repositoryClass="KejawenLab\Semart\Skeleton\Repository\CompanyRepository")
 *
 * @Gedmo\SoftDeleteable(fieldName="deletedAt")
 *
 * @Searchable({"code", "name"})
 * @Sortable({"code", "name"})
 *
 * @UniqueEntity(fields={"code"}, repositoryMethod="findUniqueBy", message="label.crud.non_unique_or_deleted")
 * @UniqueEntity(fields={"taxNumber"}, repositoryMethod="findUniqueBy", message="label.crud.non_unique_or_deleted")
 *
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class Company implements CompanyInterface
{
    use BlameableEntity;
    use CodeNameableTrait;
    use PrimaryableTrait;
    use SoftDeleteableEntity;
    use TimestampableEntity;

    /**
     * @ORM\ManyToOne(targetEntity="KejawenLab\Semart\Skeleton\Entity\CompanyGroup", fetch="EAGER")
     * @ORM\JoinColumn(name="perusahaan_grup_id", referencedColumnName="id")
     *
     * @Groups({"read"})
     **/
    private $companyGroup;

    /**
     * @ORM\Column(name="logo_perusahaan", type="string", length=255, nullable=true)
     *
     * @Assert\Length(max=255)
     *
     * @Groups({"read"})
     */
    private $logoImage;

    /**
     * @ORM\Column(name="tanggal_berdiri", type="date")
     *
     * @Assert\NotBlank()
     *
     * @Groups({"read"})
     */
    private $birthday;

    /**
     * @ORM\Column(name="alamat", type="string", length=255)
     *
     * @Assert\NotBlank()
     * @Assert\Length(max=255)
     *
     * @Groups({"read"})
     */
    private $address;

    /**
     * @ORM\ManyToOne(targetEntity="KejawenLab\Semart\Skeleton\Entity\SubDistrict", fetch="EAGER")
     * @ORM\JoinColumn(name="kecamatan_id", referencedColumnName="id")
     *
     * @Assert\NotBlank()
     *
     * @Groups({"read"})
     **/
    private $subDistrict;

    /**
     * @ORM\ManyToOne(targetEntity="KejawenLab\Semart\Skeleton\Entity\District", fetch="EAGER")
     * @ORM\JoinColumn(name="kabupaten_id", referencedColumnName="id")
     *
     * @Assert\NotBlank()
     *
     * @Groups({"read"})
     **/
    private $district;

    /**
     * @ORM\ManyToOne(targetEntity="KejawenLab\Semart\Skeleton\Entity\Province", fetch="EAGER")
     * @ORM\JoinColumn(name="propinsi_id", referencedColumnName="id")
     *
     * @Assert\NotBlank()
     *
     * @Groups({"read"})
     **/
    private $province;

    /**
     * @ORM\Column(name="kodepos", type="string", length=5, nullable=true)
     *
     * @Assert\NotBlank()
     * @Assert\Length(max=5)
     *
     * @Groups({"read"})
     */
    private $postalCode;

    /**
     * @ORM\Column(name="no_telepon", type="string", length=17)
     *
     * @Assert\NotBlank()
     * @Assert\Length(max=17)
     *
     * @Groups({"read"})
     */
    private $phoneNumber;

    /**
     * @ORM\Column(name="email", type="string", length=255)
     *
     * @Assert\NotBlank()
     * @Assert\Length(max=255)
     *
     * @Groups({"read"})
     */
    private $email;

    /**
     * @ORM\Column(name="npwp", type="string", length=27, unique=true)
     *
     * @Assert\NotBlank()
     * @Assert\Length(max=27)
     *
     * @Groups({"read"})
     */
    private $taxNumber;

    public function getCompanyGroup(): ?CompanyGroupInterface
    {
        return $this->companyGroup;
    }

    public function setCompanyGroup(CompanyGroupInterface $companyGroup): void
    {
        $this->companyGroup = $companyGroup;
    }

    public function getLogoImage(): ?string
    {
        return $this->logoImage;
    }

    public function setLogoImage(string $logoImage): void
    {
        $this->logoImage = $logoImage;
    }

    public function getBirthday(): ?\DateTime
    {
        return $this->birthday;
    }

    public function setBirthday(\DateTime $birthday): void
    {
        $this->birthday = $birthday;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(string $address): void
    {
        $this->address = $address;
    }

    public function getSubDistrict(): ?SubDistrictInterface
    {
        return $this->subDistrict;
    }

    public function setSubDistrict(SubDistrictInterface $subDistrict): void
    {
        $this->subDistrict = $subDistrict;
    }

    public function getDistrict(): ?DistrictInterface
    {
        return $this->district;
    }

    public function setDistrict(DistrictInterface $district): void
    {
        $this->district = $district;
    }

    public function getProvince(): ?ProvinceInterface
    {
        return $this->province;
    }

    public function setProvince(ProvinceInterface $province): void
    {
        $this->province = $province;
    }

    public function getPostalCode(): ?string
    {
        return $this->postalCode;
    }

    public function setPostalCode(string $postalCode): void
    {
        $this->postalCode = $postalCode;
    }

    public function getPhoneNumber(): ?string
    {
        return $this->phoneNumber;
    }

    public function setPhoneNumber(string $phoneNumber): void
    {
        $this->phoneNumber = $phoneNumber;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function getTaxNumber(): ?string
    {
        return $this->taxNumber;
    }

    public function setTaxNumber(string $taxNumber): void
    {
        $this->taxNumber = $taxNumber;
    }
}
