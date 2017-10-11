<?php

namespace KejawenLab\Application\SemarHris\Component\Employee\Model;

use KejawenLab\Application\SemarHris\Component\Job\Model\JobTitleInterface;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@personahris.com>
 */
interface EmployeeInterface
{
    /**
     * @return string
     */
    public function getId(): string;

    /**
     * @return null|JobTitleInterface
     */
    public function getJobTitle(): ? JobTitleInterface;

    /**
     * @param JobTitleInterface $jobTitle
     */
    public function setJobTitle(JobTitleInterface $jobTitle = null): void;
}
