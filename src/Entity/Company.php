<?php

namespace KejawenLab\Application\SemartHris\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use Doctrine\ORM\Mapping as ORM;
use KejawenLab\Application\SemartHris\Component\Address\Model\CityInterface;
use KejawenLab\Application\SemartHris\Component\Address\Model\RegionInterface;
use KejawenLab\Application\SemartHris\Component\Company\Model\CompanyInterface;
use KejawenLab\Application\SemartHris\Util\StringUtil;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="companies", indexes={@ORM\Index(name="companies_idx", columns={"code", "name"})})
 *
 * @ApiResource(
 *     attributes={
 *         "filters"={
 *             "code.search",
 *             "name.search"
 *         },
 *         "normalization_context"={"groups"={"read"}},
 *         "denormalization_context"={"groups"={"write"}}
 *     }
 * )
 *
 * @UniqueEntity("code")
 *
 * @author Muhamad Surya Iksanudin <surya.iksanudin@kejawenlab.id>
 */
class Company implements CompanyInterface
{
    /**
     * @Groups({"read", "write"})
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="UUID")
     * @ORM\Column(type="guid")
     *
     * @var string
     */
    private $id;

    /**
     * @Groups({"write", "read"})
     * @ORM\ManyToOne(targetEntity="KejawenLab\Application\SemartHris\Entity\Company", fetch="EAGER")
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id")
     * @ApiSubresource()
     *
     * @var CompanyInterface
     */
    private $parent;

    /**
     * @Groups({"read", "write"})
     * @ORM\Column(type="string", length=7)
     * @Assert\Length(max=7)
     * @Assert\NotBlank()
     *
     * @var string
     */
    private $code;

    /**
     * @Groups({"read", "write"})
     * @ORM\Column(type="string")
     * @Assert\NotBlank()
     *
     * @var string
     */
    private $name;

    /**
     * @Groups({"read", "write"})
     * @ORM\Column(type="date")
     * @Assert\NotBlank()
     *
     * @var \DateTimeInterface
     */
    private $birthDay;

    /**
     * @Groups({"read", "write"})
     * @ORM\Column(type="string")
     * @Assert\NotBlank()
     * @Assert\Email()
     *
     * @var string
     */
    private $email;

    /**
     * @Groups({"read", "write"})
     * @ORM\Column(type="string")
     * @Assert\NotBlank()
     *
     * @var string
     */
    private $address;

    /**
     * @Groups({"write", "read"})
     * @ORM\ManyToOne(targetEntity="KejawenLab\Application\SemartHris\Entity\Region", fetch="EAGER")
     * @ORM\JoinColumn(name="region_id", referencedColumnName="id")
     * @Assert\NotBlank()
     * @ApiSubresource()
     *
     * @var RegionInterface
     */
    private $region;

    /**
     * @Groups({"write", "read"})
     * @ORM\ManyToOne(targetEntity="KejawenLab\Application\SemartHris\Entity\City", fetch="EAGER")
     * @ORM\JoinColumn(name="city_id", referencedColumnName="id")
     * @Assert\NotBlank()
     * @ApiSubresource()
     *
     * @var CityInterface
     */
    private $city;

    /**
     * @Groups({"read", "write"})
     * @ORM\Column(type="string", length=5)
     * @Assert\Length(max=5)
     * @Assert\NotBlank()
     *
     * @var string
     */
    private $postalCode;

    /**
     * @Groups({"read", "write"})
     * @ORM\Column(type="string", length=17)
     * @Assert\Length(max=17)
     * @Assert\NotBlank()
     *
     * @var string
     */
    private $phoneNumber;

    /**
     * @Groups({"read", "write"})
     * @ORM\Column(type="string", length=11)
     * @Assert\Length(max=11)
     * @Assert\NotBlank()
     *
     * @var string
     */
    private $faxNumber;

    /**
     * @Groups({"read", "write"})
     * @ORM\Column(type="string")
     * @Assert\NotBlank()
     *
     * @var string
     */
    private $taxNumber;

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
    public function getParent(): ? CompanyInterface
    {
        return $this->parent;
    }

    /**
     * @param CompanyInterface|null $parent
     */
    public function setParent(CompanyInterface $parent = null): void
    {
        $this->parent = $parent;
    }

    /**
     * @return string
     */
    public function getCode(): string
    {
        return (string) $this->code;
    }

    /**
     * @param string $code
     */
    public function setCode(string $code): void
    {
        $this->code = StringUtil::uppercase($code);
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return (string) $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = StringUtil::uppercase($name);
    }

    /**
     * @return \DateTimeInterface
     */
    public function getBirthDay(): ? \DateTimeInterface
    {
        return $this->birthDay;
    }

    /**
     * @param \DateTimeInterface $birthDay
     */
    public function setBirthDay(\DateTimeInterface $birthDay): void
    {
        $this->birthDay = $birthDay;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return (string) $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail(string $email): void
    {
        $this->email = $email;
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
    public function setRegion(RegionInterface $region = null): void
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
    public function setCity(CityInterface $city = null): void
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
     * @return string
     */
    public function getTaxNumber(): string
    {
        return (string) $this->taxNumber;
    }

    /**
     * @param string $taxNumber
     */
    public function setTaxNumber(string $taxNumber): void
    {
        $this->taxNumber = $taxNumber;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return sprintf('%s - %s', $this->getCode(), $this->getName());
    }

    /**
     * @return string
     */
    public function getAddressClass(): string
    {
        return CompanyAddress::class;
    }
}
