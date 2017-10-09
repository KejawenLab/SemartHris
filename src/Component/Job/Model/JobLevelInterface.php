<?php

namespace Persona\Hris\Component\Job\Model;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@personahris.com>
 */
interface JobLevelInterface
{
    /**
     * @return string
     */
    public function getId(): string;

    /**
     * @return string
     */
    public function getCode(): string;

    /**
     * @return string
     */
    public function getName(): string;

    /**
     * @return null|JobLevelInterface
     */
    public function getSuperviseLevel(): ? JobLevelInterface;

    /**
     * @param JobLevelInterface|null $jobLevel
     */
    public function setSuperviseLevel(JobLevelInterface $jobLevel = null): void;
}
