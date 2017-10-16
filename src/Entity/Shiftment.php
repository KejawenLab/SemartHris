<?php

namespace KejawenLab\Application\SemartHris\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;
use KejawenLab\Application\SemartHris\Component\Attendance\Model\ShiftmentInterface;
use KejawenLab\Application\SemartHris\Util\StringUtil;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
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
 * @author Muhamad Surya Iksanudin <surya.iksanudin@kejawenlab.id>
 */
class Shiftment implements ShiftmentInterface
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
     * @ORM\Column(type="time")
     * @Assert\NotBlank()
     *
     * @var \DateTimeInterface
     */
    private $startHour;

    /**
     * @Groups({"read", "write"})
     * @ORM\Column(type="time")
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
    public function setStartHour(\DateTimeInterface $startHour = null): void
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
    public function setEndHour(\DateTimeInterface $endHour = null): void
    {
        $this->endHour = $endHour;
    }
}
