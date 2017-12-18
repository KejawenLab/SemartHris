<?php

declare(strict_types=1);

namespace KejawenLab\Application\SemartHris\Component\Skill\Model;

use KejawenLab\Application\SemartHris\Component\Employee\Model\EmployeeInterface;
use KejawenLab\Application\SemartHris\Component\Skill\SkillLevel;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
interface EmployeeSkillInterface
{
    /**
     * @return string
     */
    public function getId(): string;

    /**
     * @return null|EmployeeInterface
     */
    public function getEmployee(): ? EmployeeInterface;

    /**
     * @param EmployeeInterface|null $employee
     */
    public function setEmployee(?EmployeeInterface $employee): void;

    /**
     * @return null|SkillInterface
     */
    public function getSkill(): ? SkillInterface;

    /**
     * @param SkillInterface|null $skill
     */
    public function setSkill(?SkillInterface $skill): void;

    /**
     * @return string
     *
     * @see SkillLevel
     */
    public function getLevel(): string;

    /**
     * @return bool
     */
    public function hasCertificate(): bool;
}
