<?php

namespace KejawenLab\Application\SemartHris\Component\Skill\Model;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@kejawenlab.com>
 */
interface SkillGroupInterface
{
    /**
     * @return string
     */
    public function getId(): string;

    /**
     * @return null|SkillGroupInterface
     */
    public function getParent(): ? SkillGroupInterface;

    /**
     * @param SkillGroupInterface|null $group
     */
    public function setParent(?SkillGroupInterface $group): void;

    /**
     * @return string
     */
    public function getName(): string;
}
