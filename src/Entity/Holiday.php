<?php

declare(strict_types=1);

namespace KejawenLab\Application\SemartHris\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Blameable\Traits\BlameableEntity;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\SoftDeleteable\Traits\SoftDeleteableEntity;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use KejawenLab\Application\SemartHris\Component\Holiday\Model\HolidayInterface;
use KejawenLab\Application\SemartHris\Util\StringUtil;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity()
 * @ORM\Table(name="holidays", indexes={@ORM\Index(name="holidays_idx", columns={"name"})})
 *
 * @ApiResource(
 *     attributes={
 *         "filters"={
 *             "name.search"
 *         },
 *         "normalization_context"={"groups"={"read"}},
 *         "denormalization_context"={"groups"={"write"}}
 *     }
 * )
 *
 * @Gedmo\SoftDeleteable(fieldName="deletedAt")
 *
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class Holiday implements HolidayInterface
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
     * @Groups({"read", "write"})
     *
     * @ORM\Column(type="date")
     *
     * @Assert\NotBlank()
     *
     * @var \DateTimeInterface
     */
    private $holidayDate;

    /**
     * @Groups({"read", "write"})
     *
     * @ORM\Column(type="string")
     *
     * @Assert\NotBlank()
     *
     * @var string
     */
    private $name;

    /**
     * @return string
     */
    public function getId(): string
    {
        return (string) $this->id;
    }

    /**
     * @return \DateTimeInterface
     */
    public function getHolidayDate(): \DateTimeInterface
    {
        return $this->holidayDate ?? new \DateTime();
    }

    /**
     * @param \DateTimeInterface $holidayDate
     */
    public function setHolidayDate(\DateTimeInterface $holidayDate): void
    {
        $this->holidayDate = $holidayDate;
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
     * @return string
     */
    public function __toString(): string
    {
        return $this->getName();
    }
}
