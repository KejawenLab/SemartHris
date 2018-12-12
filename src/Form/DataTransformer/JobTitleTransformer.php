<?php

declare(strict_types=1);

namespace KejawenLab\Application\SemartHris\Form\DataTransformer;

use KejawenLab\Application\SemartHris\Component\Job\Model\JobTitleInterface;
use KejawenLab\Application\SemartHris\Component\Job\Repository\JobTitleRepositoryInterface;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class JobTitleTransformer implements DataTransformerInterface
{
    /**
     * @var JobTitleRepositoryInterface
     */
    private $jobTitleRepository;

    /**
     * @param JobTitleRepositoryInterface $jobTitleRepository
     */
    public function __construct(JobTitleRepositoryInterface $jobTitleRepository)
    {
        $this->jobTitleRepository = $jobTitleRepository;
    }

    /**
     * @param object $jobTitle
     *
     * @return string
     */
    public function transform($jobTitle): string
    {
        if (null === $jobTitle) {
            return '';
        }

        return $jobTitle->getId();
    }

    /**
     * @param string $jobTitleId
     *
     * @return null|JobTitleInterface
     */
    public function reverseTransform($jobTitleId)
    {
        if (!$jobTitleId) {
            return null;
        }

        $jobTitle = $this->jobTitleRepository->find($jobTitleId);
        if (null === $jobTitle) {
            throw new TransformationFailedException(sprintf('Job title with id "%s" is not exist.', $jobTitleId));
        }

        return $jobTitle;
    }
}
