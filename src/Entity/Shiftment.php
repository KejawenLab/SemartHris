<?php

declare(strict_types=1);

namespace KejawenLab\Application\SemartHris\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Blameable\Traits\BlameableEntity;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\SoftDeleteable\Traits\SoftDeleteableEntity;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use KejawenLab\Application\SemartHris\Component\Attendance\Model\ShiftmentInterface;
use KejawenLab\Application\SemartHris\Util\StringUtil;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity()
 * @ORM\Table(name="shiftments", indexes={@ORM\Index(name="shiftments_idx", columns={"code", "name"})})
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
 * @Gedmo\SoftDeleteable(fieldName="deletedAt")
 *
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class Shiftment implements ShiftmentInterface
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
     * @ORM\Column(type="string", length=7)
     *
     * @Assert\Length(max=7)
     * @Assert\NotBlank()
     *
     * @var string
     */
    private $code;

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
     * @Groups({"read", "write"})
     *
     * @ORM\Column(type="time")
     *
     * @Assert\NotBlank()
     *
     * @var \DateTimeInterface
     */
    private $startHour;

    /**
     * @Groups({"read", "write"})
     *
     * @ORM\Column(type="time")
     *
     * @Assert\NotBlank()
     *
     * @var \DateTimeInterface
     */
    private $endHour;

    /**
     * @return string
     */
    public function getId(): string
    {
        return (string) $this->id;
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
     * @return \DateTimeInterface|null
     */
    public function getStartHour(): ? \DateTimeInterface
    {
        return $this->startHour;
    }

    /**
     * @param \DateTimeInterface|null $startHour
     */
    public function setStartHour(?\DateTimeInterface $startHour): void
    {
        $this->startHour = $startHour;
    }

    /**
     * @return \DateTimeInterface|null
     */
    public function getEndHour(): ? \DateTimeInterface
    {
        return $this->endHour;
    }

    /**
     * @param \DateTimeInterface|null $endHour
     */
    public function setEndHour(?\DateTimeInterface $endHour): void
    {
        $this->endHour = $endHour;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return sprintf('%s - %s (%s - %s)', $this->getCode(), $this->getName(), $this->getStartHour()->format('H:i'), $this->getEndHour()->format('H:i'));
    }
}
