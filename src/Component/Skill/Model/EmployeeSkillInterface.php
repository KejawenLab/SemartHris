<?php

namespace KejawenLab\Application\SemarHris\Component\Skill\Model;

use KejawenLab\Application\SemarHris\Component\Employee\Model\EmployeeInterface;
use KejawenLab\Application\SemarHris\Component\Skill\SkillLevel;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@kejawenlab.com>
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
    public function setEmployee(EmployeeInterface $employee = null): void;

    /**
     * @return null|SkillInterface
     */
    public function getSkill(): ? SkillInterface;

    /**
     * @param SkillInterface|null $skill
     */
    public function setSkill(SkillInterface $skill = null): void;

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
