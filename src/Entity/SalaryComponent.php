<?php

declare(strict_types=1);

namespace KejawenLab\Application\SemartHris\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Blameable\Traits\BlameableEntity;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\SoftDeleteable\Traits\SoftDeleteableEntity;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use KejawenLab\Application\SemartHris\Component\Salary\Model\ComponentInterface;
use KejawenLab\Application\SemartHris\Component\Salary\Service\ValidateStateType;
use KejawenLab\Application\SemartHris\Util\StringUtil;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity()
 * @ORM\Table(name="salary_components", indexes={@ORM\Index(name="salary_components_idx", columns={"code", "name"})})
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
class SalaryComponent implements ComponentInterface
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
     * @ORM\Column(type="string", length=1)
     *
     * @Assert\NotBlank()
     * @Assert\Length(max="1")
     * @Assert\Choice(callback="getStateChoices")
     *
     * @var string
     */
    private $state;

    /**
     * @Groups({"read", "write"})
     *
     * @ORM\Column(type="boolean")
     *
     * @var bool
     */
    private $fixed;

    public function __construct()
    {
        $this->fixed = false;
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
     * @return string
     */
    public function getState(): string
    {
        return (string) $this->state;
    }

    /**
     * @return string
     */
    public function getStateText(): string
    {
        return ValidateStateType::convertToText($this->state);
    }

    /**
     * @param string $state
     */
    public function setState(string $state)
    {
        if (!ValidateStateType::isValidType($state)) {
            throw new \InvalidArgumentException(sprintf('"%s" is not valid state.', $state));
        }

        $this->state = $state;
    }

    /**
     * @return bool
     */
    public function isFixed(): bool
    {
        return $this->fixed ?? false;
    }

    /**
     * @return bool
     */
    public function getFixed(): bool
    {
        return $this->isFixed();
    }

    /**
     * @param bool $fixed
     */
    public function setFixed(bool $fixed): void
    {
        $this->fixed = $fixed;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return sprintf('%s - %s', $this->getCode(), $this->getName());
    }

    /**
     * @return array
     */
    public function getStateChoices(): array
    {
        return ValidateStateType::getTypes();
    }
}
