<?php

namespace KejawenLab\Application\SemarHris\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use Doctrine\ORM\Mapping as ORM;
use KejawenLab\Application\SemarHris\Component\Skill\Model\SkillGroupInterface;
use KejawenLab\Application\SemarHris\Component\Skill\Model\SkillInterface;
use KejawenLab\Application\SemarHris\Util\StringUtil;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="skills", indexes={@ORM\Index(name="skills_idx", columns={"name"})})
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
 * @author Muhamad Surya Iksanudin <surya.iksanudin@kejawenlab.id>
 */
class Skill implements SkillInterface
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
     * @ORM\ManyToOne(targetEntity="KejawenLab\Application\SemarHris\Entity\SkillGroup", fetch="EAGER")
     * @ORM\JoinColumn(name="skill_group_id", referencedColumnName="id")
     * @Assert\NotBlank()
     * @ApiSubresource()
     *
     * @var SkillGroupInterface
     */
    private $skillGroup;

    /**
     * @Groups({"read", "write"})
     * @ORM\Column(type="string")
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
     * @return SkillGroupInterface
     */
    public function getSkillGroup(): ? SkillGroupInterface
    {
        return $this->skillGroup;
    }

    /**
     * @param SkillGroupInterface $skillGroup
     */
    public function setSkillGroup(SkillGroupInterface $skillGroup = null): void
    {
        $this->skillGroup = $skillGroup;
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
        if ($this->getSkillGroup()) {
            return sprintf('%s - %s', $this->getSkillGroup()->getName(), $this->getName());
        }

        return $this->getName();
    }
}
