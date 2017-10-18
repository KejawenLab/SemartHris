<?php

namespace KejawenLab\Application\SemartHris\Component\Job\Model;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@kejawenlab.com>
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
