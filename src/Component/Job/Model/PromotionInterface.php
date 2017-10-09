<?php

namespace Persona\Hris\Component\Job\Model;

use Persona\Hris\Component\Job\MutationType;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@personahris.com>
 */
interface PromotionInterface extends MutationInterface
{
    /**
     * @return string
     *
     * @see MutationType
     */
    public function getType(): string;

    /**
     * @return null|JobTitleInterface
     */
    public function getCurrentJobTitle(): ? JobTitleInterface;

    /**
     * @param JobTitleInterface $jobTitle
     */
    public function setCurrentJobTitle(JobTitleInterface $jobTitle = null): void;

    /**
     * @return null|JobTitleInterface
     */
    public function getNewJobTitle(): ? JobTitleInterface;

    /**
     * @param JobTitleInterface $jobTitle
     */
    public function setNewJobTitle(JobTitleInterface $jobTitle = null): void;
}
