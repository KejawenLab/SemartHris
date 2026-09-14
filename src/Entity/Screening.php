<?php

declare(strict_types=1);

namespace KejawenLab\Application\SemartHris\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Blameable\Traits\BlameableEntity;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\SoftDeleteable\Traits\SoftDeleteableEntity;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use KejawenLab\Application\SemartHris\Component\Screening\RecruiterDecision;
use KejawenLab\Application\SemartHris\Component\Screening\ScreeningOutcome;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Result of one AI phone screening call.
 * Ported from HireCall ScreeningResult model. The outcome is
 * produced by ScreeningScorer, the recruiter decision stays manual.
 *
 * @ORM\Entity()
 * @ORM\Table(name="screenings", indexes={@ORM\Index(name="screenings_idx", columns={"outcome", "call_run_id"})})
 *
 * @ApiResource(
 *     attributes={
 *         "filters"={
 *             "outcome.search"
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
class Screening
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
     * @ORM\ManyToOne(targetEntity="KejawenLab\Application\SemartHris\Entity\ScreeningCandidate", fetch="EAGER")
     * @ORM\JoinColumn(name="candidate_id", referencedColumnName="id", nullable=false)
     *
     * @Assert\NotBlank()
     *
     * @var ScreeningCandidate
     */
    private $candidate;

    /**
     * @Groups({"read", "write"})
     *
     * @ORM\Column(type="string", length=15)
     *
     * @Assert\NotBlank()
     * @Assert\Choice(callback="getOutcomeChoices")
     *
     * @var string
     */
    private $outcome = ScreeningOutcome::INCOMPLETE;

    /**
     * @Groups({"read", "write"})
     *
     * @ORM\Column(type="integer")
     *
     * @var int
     */
    private $durationSeconds = 0;

    /**
     * Matched evidence per criterion, e.g. {"experience": true}.
     *
     * @Groups({"read", "write"})
     *
     * @ORM\Column(type="json_array", nullable=true)
     *
     * @var null|array
     */
    private $evidence;

    /**
     * @Groups({"read"})
     *
     * @ORM\Column(type="text", nullable=true)
     *
     * @var null|string
     */
    private $transcript;

    /**
     * @Groups({"read"})
     *
     * @ORM\Column(type="string", length=255, nullable=true, name="call_run_id")
     *
     * @var null|string
     */
    private $callRunId;

    /**
     * @Groups({"read", "write"})
     *
     * @ORM\Column(type="string", length=15, nullable=true, name="recruiter_decision")
     *
     * @Assert\Choice(callback="getDecisionChoices")
     *
     * @var null|string
     */
    private $recruiterDecision;

    /**
     * @Groups({"read"})
     *
     * @ORM\Column(type="datetime", nullable=true, name="completed_at")
     *
     * @var null|\DateTimeInterface
     */
    private $completedAt;

    /**
     * @return array
     */
    public static function getOutcomeChoices(): array
    {
        return [
            ScreeningOutcome::QUALIFIED,
            ScreeningOutcome::MAYBE,
            ScreeningOutcome::NOT_FIT,
            ScreeningOutcome::INCOMPLETE,
        ];
    }

    /**
     * @return array
     */
    public static function getDecisionChoices(): array
    {
        return [
            RecruiterDecision::INTERVIEW,
            RecruiterDecision::BACKUP,
            RecruiterDecision::PASS,
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
     * @return null|ScreeningCandidate
     */
    public function getCandidate(): ?ScreeningCandidate
    {
        return $this->candidate;
    }

    /**
     * @param ScreeningCandidate $candidate
     */
    public function setCandidate(ScreeningCandidate $candidate): void
    {
        $this->candidate = $candidate;
    }

    /**
     * @return string
     */
    public function getOutcome(): string
    {
        return $this->outcome;
    }

    /**
     * @param string $outcome
     */
    public function setOutcome(string $outcome): void
    {
        $this->outcome = $outcome;
    }

    /**
     * @return int
     */
    public function getDurationSeconds(): int
    {
        return $this->durationSeconds;
    }

    /**
     * @param int $durationSeconds
     */
    public function setDurationSeconds(int $durationSeconds): void
    {
        $this->durationSeconds = $durationSeconds;
    }

    /**
     * @return null|array
     */
    public function getEvidence(): ?array
    {
        return $this->evidence;
    }

    /**
     * @param null|array $evidence
     */
    public function setEvidence(?array $evidence): void
    {
        $this->evidence = $evidence;
    }

    /**
     * @return null|string
     */
    public function getTranscript(): ?string
    {
        return $this->transcript;
    }

    /**
     * @param null|string $transcript
     */
    public function setTranscript(?string $transcript): void
    {
        $this->transcript = $transcript;
    }

    /**
     * @return null|string
     */
    public function getCallRunId(): ?string
    {
        return $this->callRunId;
    }

    /**
     * @param null|string $callRunId
     */
    public function setCallRunId(?string $callRunId): void
    {
        $this->callRunId = $callRunId;
    }

    /**
     * @return null|string
     */
    public function getRecruiterDecision(): ?string
    {
        return $this->recruiterDecision;
    }

    /**
     * @param null|string $recruiterDecision
     */
    public function setRecruiterDecision(?string $recruiterDecision): void
    {
        $this->recruiterDecision = $recruiterDecision;
    }

    /**
     * @return null|\DateTimeInterface
     */
    public function getCompletedAt(): ?\DateTimeInterface
    {
        return $this->completedAt;
    }

    /**
     * @param null|\DateTimeInterface $completedAt
     */
    public function setCompletedAt(?\DateTimeInterface $completedAt): void
    {
        $this->completedAt = $completedAt;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return sprintf('%s (%s)', (string) $this->candidate, $this->outcome);
    }
}
