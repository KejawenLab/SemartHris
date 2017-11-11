<?php

namespace KejawenLab\Application\SemartHris\Component\Job\Model;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@kejawenlab.com>
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
    public function getParent(): ? JobLevelInterface;

    /**
     * @param JobLevelInterface|null $jobLevel
     */
    public function setParent(?JobLevelInterface $jobLevel): void;
}
