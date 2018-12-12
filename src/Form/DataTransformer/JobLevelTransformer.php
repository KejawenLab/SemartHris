<?php

declare(strict_types=1);

namespace KejawenLab\Application\SemartHris\Form\DataTransformer;

use KejawenLab\Application\SemartHris\Component\Job\Model\JobLevelInterface;
use KejawenLab\Application\SemartHris\Component\Job\Repository\JobLevelRepositoryInterface;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class JobLevelTransformer implements DataTransformerInterface
{
    /**
     * @var JobLevelRepositoryInterface
     */
    private $jobLevelRepository;

    /**
     * @param JobLevelRepositoryInterface $jobLevelRepository
     */
    public function __construct(JobLevelRepositoryInterface $jobLevelRepository)
    {
        $this->jobLevelRepository = $jobLevelRepository;
    }

    /**
     * @param object $jobLevel
     *
     * @return string
     */
    public function transform($jobLevel): string
    {
        if (null === $jobLevel) {
            return '';
        }

        return $jobLevel->getId();
    }

    /**
     * @param string $jobLevelId
     *
     * @return null|JobLevelInterface
     */
    public function reverseTransform($jobLevelId)
    {
        if (!$jobLevelId) {
            return null;
        }

        $jobLevel = $this->jobLevelRepository->find($jobLevelId);
        if (null === $jobLevel) {
            throw new TransformationFailedException(sprintf('Job level with id "%s" is not exist.', $jobLevelId));
        }

        return $jobLevel;
    }
}
