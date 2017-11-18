<?php

declare(strict_types=1);

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
    public function getParent(): ? self;

    /**
     * @param JobLevelInterface|null $jobLevel
     */
    public function setParent(?self $jobLevel): void;
}
