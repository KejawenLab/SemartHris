<?php

declare(strict_types=1);

namespace KejawenLab\Application\SemartHris\Twig;

use KejawenLab\Application\SemartHris\Component\Employee\Model\EmployeeInterface;
use KejawenLab\Application\SemartHris\Component\Employee\Repository\EmployeeRepositoryInterface;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class EmployeeExtension extends \Twig_Extension
{
    /**
     * @var EmployeeRepositoryInterface
     */
    private $repository;

    /**
     * @param EmployeeRepositoryInterface $repository
     */
    public function __construct(EmployeeRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @return array
     */
    public function getFunctions(): array
    {
        return [
            new \Twig_SimpleFunction('semarthris_create_employee', [$this, 'getEmployee']),
        ];
    }

    /**
     * @param string $id
     *
     * @return EmployeeInterface|null
     */
    public function getEmployee(?string $id): ? EmployeeInterface
    {
        return $this->repository->find($id);
    }
}
