<?php

declare(strict_types=1);

namespace KejawenLab\Application\SemartHris\Form\DataTransformer;

use KejawenLab\Application\SemartHris\Component\Company\Model\DepartmentInterface;
use KejawenLab\Application\SemartHris\Component\Company\Repository\DepartmentRepositoryInterface;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class DepartmentTransformer implements DataTransformerInterface
{
    /**
     * @var DepartmentRepositoryInterface
     */
    private $departmentRepository;

    /**
     * @param DepartmentRepositoryInterface $departmentRepository
     */
    public function __construct(DepartmentRepositoryInterface $departmentRepository)
    {
        $this->departmentRepository = $departmentRepository;
    }

    /**
     * @param object $department
     *
     * @return string
     */
    public function transform($department): string
    {
        if (null === $department) {
            return '';
        }

        return $department->getId();
    }

    /**
     * @param string $departmentId
     *
     * @return null|DepartmentInterface
     */
    public function reverseTransform($departmentId)
    {
        if (!$departmentId) {
            return null;
        }

        $department = $this->departmentRepository->find($departmentId);
        if (null === $department) {
            throw new TransformationFailedException(sprintf('Department with id "%s" is not exist.', $departmentId));
        }

        return $department;
    }
}
