<?php

declare(strict_types=1);

namespace KejawenLab\Application\SemartHris\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Blameable\Traits\BlameableEntity;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\SoftDeleteable\Traits\SoftDeleteableEntity;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use KejawenLab\Application\SemartHris\Component\Contract\Model\ContractInterface;
use KejawenLab\Application\SemartHris\Component\Contract\Service\ValidateContractType;
use KejawenLab\Application\SemartHris\Util\StringUtil;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity()
 * @ORM\Table(name="contracts")
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
class Contract implements ContractInterface
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
     * @ORM\Column(type="string", length=1)
     *
     * @Assert\Length(max=1)
     * @Assert\NotBlank()
     * @Assert\Choice(callback="getContractTypeChoices")
     *
     * @var string
     */
    private $type;

    /**
     * @Groups({"read", "write"})
     *
     * @ORM\Column(type="string", length=27)
     *
     * @Assert\Length(max=27)
     * @Assert\NotBlank()
     *
     * @var string
     */
    private $letterNumber;

    /**
     * @Groups({"read", "write"})
     *
     * @ORM\Column(type="string")
     *
     * @Assert\NotBlank()
     *
     * @var string
     */
    private $subject;

    /**
     * @Groups({"read", "write"})
     *
     * @ORM\Column(type="string", nullable=true)
     *
     * @var string
     */
    private $description;

    /**
     * @Groups({"read", "write"})
     *
     * @ORM\Column(type="date")
     *
     * @Assert\NotBlank()
     *
     * @var \DateTimeInterface
     */
    private $startDate;

    /**
     * @Groups({"read", "write"})
     *
     * @ORM\Column(type="date", nullable=true)
     *
     * @var \DateTimeInterface|null
     */
    private $endDate;

    /**
     * @Groups({"read", "write"})
     *
     * @ORM\Column(type="date")
     *
     * @Assert\NotBlank()
     *
     * @var \DateTimeInterface
     */
    private $signedDate;

    /**
     * @Groups({"read", "write"})
     *
     * @ORM\Column(type="array", nullable=true)
     *
     * @var array
     */
    private $tags;

    /**
     * @Groups({"read"})
     *
     * @ORM\Column(type="boolean")
     *
     * @var bool
     */
    private $used;

    public function __construct()
    {
        $this->used = false;
        $this->tags = [];
    }

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
    public function getType(): string
    {
        return (string) $this->type;
    }

    /**
     * @return string
     */
    public function getTypeText(): string
    {
        return ValidateContractType::convertToText($this->type);
    }

    /**
     * @param string $type
     */
    public function setType(string $type): void
    {
        if (!ValidateContractType::isValidType($type)) {
            throw new \InvalidArgumentException(sprintf('"%s" is not valid contract type.', $type));
        }

        $this->type = $type;
    }

    /**
     * @return string
     */
    public function getLetterNumber(): string
    {
        return (string) $this->letterNumber;
    }

    /**
     * @param string $letterNumber
     */
    public function setLetterNumber(string $letterNumber): void
    {
        $this->letterNumber = $letterNumber;
    }

    /**
     * @return string
     */
    public function getSubject(): string
    {
        return (string) $this->subject;
    }

    /**
     * @param string $subject
     */
    public function setSubject(string $subject): void
    {
        $this->subject = StringUtil::uppercase($subject);
    }

    /**
     * @return string
     */
    public function getDescription(): ? string
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }

    /**
     * @return \DateTimeInterface
     */
    public function getStartDate(): \DateTimeInterface
    {
        return $this->startDate ?? new \DateTime();
    }

    /**
     * @param \DateTimeInterface $startDate
     */
    public function setStartDate(\DateTimeInterface $startDate): void
    {
        $this->startDate = $startDate;
    }

    /**
     * @return \DateTimeInterface|null
     */
    public function getEndDate(): ? \DateTimeInterface
    {
        return $this->endDate;
    }

    /**
     * @param \DateTimeInterface|null $endDate
     */
    public function setEndDate(?\DateTimeInterface $endDate): void
    {
        $this->endDate = $endDate;
    }

    /**
     * @return \DateTimeInterface
     */
    public function getSignedDate(): \DateTimeInterface
    {
        return $this->signedDate ?? new \DateTime();
    }

    /**
     * @param \DateTimeInterface $signedDate
     */
    public function setSignedDate(\DateTimeInterface $signedDate): void
    {
        $this->signedDate = $signedDate;
    }

    /**
     * @return array
     */
    public function getTags(): array
    {
        return $this->tags;
    }

    /**
     * @param array $tags
     */
    public function setTags(array $tags = []): void
    {
        $this->tags = [];
        foreach ($tags as $tag) {
            $tag = StringUtil::uppercase($tag);
            if ($tag && !in_array($tag, $this->tags)) {
                $this->tags[] = $tag;
            }
        }
    }

    /**
     * @return bool
     */
    public function isUsed(): bool
    {
        return $this->used;
    }

    /**
     * @return bool
     */
    public function getUsed(): bool
    {
        return $this->isUsed();
    }

    /**
     * @param bool $used
     */
    public function setUsed(bool $used): void
    {
        $this->used = $used;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return sprintf('%s - %s', $this->getLetterNumber(), $this->getSubject());
    }

    /**
     * @return array
     */
    public function getContractTypeChoices(): array
    {
        return ValidateContractType::getTypes();
    }
}
