<?php

declare(strict_types=1);

namespace KejawenLab\Application\SemartHris\Form\DataTransformer;

use KejawenLab\Application\SemartHris\Component\Reason\Model\ReasonInterface;
use KejawenLab\Application\SemartHris\Component\Reason\Repository\ReasonRepositoryInterface;
use KejawenLab\Application\SemartHris\Component\Salary\Repository\ComponentRepositoryInterface;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class SalaryComponentTransformer implements DataTransformerInterface
{
    /**
     * @var ReasonRepositoryInterface
     */
    private $salaryComponentRepository;

    /**
     * @param ComponentRepositoryInterface $salaryComponentRepository
     */
    public function __construct(ComponentRepositoryInterface $salaryComponentRepository)
    {
        $this->salaryComponentRepository = $salaryComponentRepository;
    }

    /**
     * @param object $salaryComponent
     *
     * @return string
     */
    public function transform($salaryComponent): string
    {
        if (null === $salaryComponent) {
            return '';
        }

        return $salaryComponent->getId();
    }

    /**
     * @param string $salaryComponentId
     *
     * @return null|ReasonInterface
     */
    public function reverseTransform($salaryComponentId)
    {
        if (!$salaryComponentId) {
            return null;
        }

        $salaryComponent = $this->salaryComponentRepository->find($salaryComponentId);
        if (null === $salaryComponent) {
            throw new TransformationFailedException(sprintf('Salary component with id "%s" is not exist.', $salaryComponentId));
        }

        return $salaryComponent;
    }
}
