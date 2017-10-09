<?php

namespace Persona\Hris\Component\Skill\Model;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@personahris.com>
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
    public function setParent(SkillGroupInterface $group = null): void;

    /**
     * @return string
     */
    public function getName(): string;
}
