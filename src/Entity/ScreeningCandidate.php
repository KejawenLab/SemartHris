<?php

declare(strict_types=1);

namespace KejawenLab\Application\SemartHris\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Blameable\Traits\BlameableEntity;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\SoftDeleteable\Traits\SoftDeleteableEntity;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use KejawenLab\Application\SemartHris\Component\Screening\ScreeningStatus;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Job applicant waiting for AI phone screening.
 * Ported from HireCall Candidate model. Applicants are not
 * employees yet, so this entity stays separate from Employee.
 *
 * @ORM\Entity()
 * @ORM\Table(name="screening_candidates", indexes={@ORM\Index(name="screening_candidates_idx", columns={"name", "phone", "position"})})
 *
 * @ApiResource(
 *     attributes={
 *         "filters"={
 *             "name.search",
 *             "phone.search"
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
class ScreeningCandidate
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
     * @ORM\Column(type="string", length=255)
     *
     * @Assert\NotBlank()
     *
     * @var string
     */
    private $name;

    /**
     * @Groups({"read", "write"})
     *
     * @ORM\Column(type="string", length=20)
     *
     * @Assert\NotBlank()
     * @Assert\Regex(pattern="/^\+[1-9]\d{7,14}$/", message="semarthris.screening.invalid_phone")
     *
     * @var string
     */
    private $phone;

    /**
     * @Groups({"read", "write"})
     *
     * @ORM\Column(type="string", length=255)
     *
     * @Assert\NotBlank()
     *
     * @var string
     */
    private $position;

    /**
     * @Groups({"read", "write"})
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     *
     * @var null|string
     */
    private $location;

    /**
     * @Groups({"read", "write"})
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     *
     * @var null|string
     */
    private $experience;

    /**
     * @Groups({"read", "write"})
     *
     * @ORM\Column(type="string", length=15)
     *
     * @Assert\NotBlank()
     * @Assert\Choice(callback="getStatusChoices")
     *
     * @var string
     */
    private $status = ScreeningStatus::READY;

    /**
     * @Groups({"read", "write"})
     *
     * @ORM\ManyToOne(targetEntity="KejawenLab\Application\SemartHris\Entity\JobTitle", fetch="EAGER")
     * @ORM\JoinColumn(name="job_title_id", referencedColumnName="id", nullable=true)
     *
     * @var null|JobTitle
     */
    private $jobTitle;

    /**
     * @return array
     */
    public static function getStatusChoices(): array
    {
        return [
            ScreeningStatus::READY,
            ScreeningStatus::QUEUED,
            ScreeningStatus::CALLING,
            ScreeningStatus::COMPLETED,
            ScreeningStatus::NOT_ANSWERED,
            ScreeningStatus::FAILED,
        ];
    }

    /**
     * @return null|string
     */
    public function getId(): ?string
    {
        return $this->id ? (string) $this->id : null;
    }

    /**
     * @return null|string
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return null|string
     */
    public function getPhone(): ?string
    {
        return $this->phone;
    }

    /**
     * @param string $phone E.164 number, e.g. +6281234567890
     */
    public function setPhone(string $phone): void
    {
        $this->phone = $phone;
    }

    /**
     * @return null|string
     */
    public function getPosition(): ?string
    {
        return $this->position;
    }

    /**
     * @param string $position
     */
    public function setPosition(string $position): void
    {
        $this->position = $position;
    }

    /**
     * @return null|string
     */
    public function getLocation(): ?string
    {
        return $this->location;
    }

    /**
     * @param null|string $location
     */
    public function setLocation(?string $location): void
    {
        $this->location = $location;
    }

    /**
     * @return null|string
     */
    public function getExperience(): ?string
    {
        return $this->experience;
    }

    /**
     * @param null|string $experience
     */
    public function setExperience(?string $experience): void
    {
        $this->experience = $experience;
    }

    /**
     * @return string
     */
    public function getStatus(): string
    {
        return $this->status;
    }

    /**
     * @param string $status
     */
    public function setStatus(string $status): void
    {
        $this->status = $status;
    }

    /**
     * @return null|JobTitle
     */
    public function getJobTitle(): ?JobTitle
    {
        return $this->jobTitle;
    }

    /**
     * @param null|JobTitle $jobTitle
     */
    public function setJobTitle(?JobTitle $jobTitle): void
    {
        $this->jobTitle = $jobTitle;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return (string) $this->name;
    }
}
