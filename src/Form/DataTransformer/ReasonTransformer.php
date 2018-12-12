<?php

declare(strict_types=1);

namespace KejawenLab\Application\SemartHris\Form\DataTransformer;

use KejawenLab\Application\SemartHris\Component\Reason\Model\ReasonInterface;
use KejawenLab\Application\SemartHris\Component\Reason\Repository\ReasonRepositoryInterface;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class ReasonTransformer implements DataTransformerInterface
{
    /**
     * @var ReasonRepositoryInterface
     */
    private $reasonRepository;

    /**
     * @param ReasonRepositoryInterface $reasonRepository
     */
    public function __construct(ReasonRepositoryInterface $reasonRepository)
    {
        $this->reasonRepository = $reasonRepository;
    }

    /**
     * @param object $reason
     *
     * @return string
     */
    public function transform($reason): string
    {
        if (null === $reason) {
            return '';
        }

        return $reason->getId();
    }

    /**
     * @param string $reasonId
     *
     * @return null|ReasonInterface
     */
    public function reverseTransform($reasonId)
    {
        if (!$reasonId) {
            return null;
        }

        $reason = $this->reasonRepository->find($reasonId);
        if (null === $reason) {
            throw new TransformationFailedException(sprintf('Reason with id "%s" is not exist.', $reasonId));
        }

        return $reason;
    }
}
