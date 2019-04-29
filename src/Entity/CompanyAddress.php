<?php

declare(strict_types=1);

namespace KejawenLab\Application\SemartHris\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Blameable\Traits\BlameableEntity;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\SoftDeleteable\Traits\SoftDeleteableEntity;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use KejawenLab\Application\SemartHris\Component\Address\Model\Addressable;
use KejawenLab\Application\SemartHris\Component\Address\Model\CityInterface;
use KejawenLab\Application\SemartHris\Component\Address\Model\RegionInterface;
use KejawenLab\Application\SemartHris\Component\Company\Model\CompanyAddressInterface;
use KejawenLab\Application\SemartHris\Component\Company\Model\CompanyInterface;
use KejawenLab\Application\SemartHris\Util\StringUtil;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity()
 * @ORM\Table(name="company_addresses")
 *
 * @ApiResource(
 *     attributes={
 *         "normalization_context"={"groups"={"read"}},
 *         "denormalization_context"={"groups"={"write"}}
 *     }
 * )
 *
 * @Gedmo\SoftDeleteable(fieldName="deletedAt")
 *
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class CompanyAddress implements CompanyAddressInterface
{
    use BlameableEntity;
    use SoftDeleteableEntity;
    use TimestampableEntity;

    /**
     * @Groups({"read"})
     *
     * @ORM\Id()
     * @ORM\Column(type="uuid", unique=true)
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\CustomIdGenerator(class="Ramsey\Uuid\Doctrine\UuidGenerator")
     *
     * @var string
     */
    private $id;

    /**
     * @Groups({"write", "read"})
     *
     * @ORM\ManyToOne(targetEntity="KejawenLab\Application\SemartHris\Entity\Company", fetch="EAGER")
     * @ORM\JoinColumn(name="company_id", referencedColumnName="id")
     *
     * @Assert\NotBlank()
     *
     * @var CompanyInterface
     */
    private $company;

    /**
     * @Groups({"read", "write"})
     * @ORM\Column(type="string")
     *
     * @Assert\NotBlank()
     *
     * @var string
     */
    private $address;

    /**
     * @Groups({"write", "read"})
     *
     * @ORM\ManyToOne(targetEntity="KejawenLab\Application\SemartHris\Entity\Region", fetch="EAGER")
     * @ORM\JoinColumn(name="region_id", referencedColumnName="id")
     *
     * @Assert\NotBlank()
     *
     * @var RegionInterface
     */
    private $region;

    /**
     * @Groups({"write", "read"})
     *
     * @ORM\ManyToOne(targetEntity="KejawenLab\Application\SemartHris\Entity\City", fetch="EAGER")
     * @ORM\JoinColumn(name="city_id", referencedColumnName="id")
     *
     * @Assert\NotBlank()
     *
     * @var CityInterface
     */
    private $city;

    /**
     * @Groups({"read", "write"})
     *
     * @ORM\Column(type="string", length=5)
     *
     * @Assert\Length(max=5)
     * @Assert\NotBlank()
     *
     * @var string
     */
    private $postalCode;

    /**
     * @Groups({"read", "write"})
     *
     * @ORM\Column(type="string", length=17)
     *
     * @Assert\Length(max=17)
     * @Assert\NotBlank()
     *
     * @var string
     */
    private $phoneNumber;

    /**
     * @Groups({"read", "write"})
     *
     * @ORM\Column(type="string", length=11)
     *
     * @Assert\Length(max=11)
     * @Assert\NotBlank()
     *
     * @var string
     */
    private $faxNumber;

    /**
     * @Groups({"read", "write"})
     *
     * @ORM\Column(type="boolean")
     *
     * @var bool
     */
    private $defaultAddress;

    public function __construct()
    {
        $this->defaultAddress = true;
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return (string) $this->id;
    }

    /**
     * @return null|CompanyInterface
     */
    public function getCompany(): ? CompanyInterface
    {
        return $this->company;
    }

    /**
     * @param CompanyInterface|null $company
     */
    public function setCompany(?CompanyInterface $company): void
    {
        $this->company = $company;
    }

    /**
     * @return string
     */
    public function getAddress(): string
    {
        return (string) $this->address;
    }

    /**
     * @param string $address
     */
    public function setAddress(string $address): void
    {
        $this->address = StringUtil::uppercase($address);
    }

    /**
     * @return null|RegionInterface
     */
    public function getRegion(): ? RegionInterface
    {
        return $this->region;
    }

    /**
     * @param RegionInterface|null $region
     */
    public function setRegion(?RegionInterface $region): void
    {
        $this->region = $region;
    }

    /**
     * @return null|CityInterface
     */
    public function getCity(): ? CityInterface
    {
        return $this->city;
    }

    /**
     * @param CityInterface|null $city
     */
    public function setCity(?CityInterface $city): void
    {
        $this->city = $city;
    }

    /**
     * @return string
     */
    public function getPostalCode(): string
    {
        return (string) $this->postalCode;
    }

    /**
     * @param string $postalCode
     */
    public function setPostalCode(string $postalCode): void
    {
        $this->postalCode = $postalCode;
    }

    /**
     * @return string
     */
    public function getPhoneNumber(): string
    {
        return (string) $this->phoneNumber;
    }

    /**
     * @param string $phoneNumber
     */
    public function setPhoneNumber(string $phoneNumber): void
    {
        $this->phoneNumber = $phoneNumber;
    }

    /**
     * @return string
     */
    public function getFaxNumber(): string
    {
        return (string) $this->faxNumber;
    }

    /**
     * @param string $faxNumber
     */
    public function setFaxNumber(string $faxNumber): void
    {
        $this->faxNumber = $faxNumber;
    }

    /**
     * @return bool
     */
    public function isDefaultAddress(): bool
    {
        return $this->defaultAddress;
    }

    /**
     * @param bool $defaultAddress
     */
    public function setDefaultAddress(bool $defaultAddress): void
    {
        $this->defaultAddress = $defaultAddress;
    }

    /**
     * @return Addressable|null
     */
    public function getAddressable(): ? Addressable
    {
        return $this->getCompany();
    }
}
