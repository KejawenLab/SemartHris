<?php

namespace Persona\Hris\Component\Employee\Model;

use Persona\Hris\Component\Job\Model\JobTitleInterface;

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
