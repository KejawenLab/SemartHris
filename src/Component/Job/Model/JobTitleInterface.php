<?php

declare(strict_types=1);

namespace KejawenLab\Application\SemartHris\Component\Job\Model;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
interface JobTitleInterface
{
    /**
     * @return string
     */
    public function getId(): string;

    /**
     * @return null|JobLevelInterface
     */
    public function getJobLevel(): ? JobLevelInterface;

    /**
     * @param JobLevelInterface|null $jobLevel
     */
    public function setJobLevel(?JobLevelInterface $jobLevel): void;

    /**
     * @return string
     */
    public function getCode(): string;

    /**
     * @return string
     */
    public function getName(): string;
}
