<?php

declare(strict_types=1);

namespace KejawenLab\Application\SemartHris\Component\Employee\Repository;

use KejawenLab\Application\SemartHris\Component\Employee\Model\EmployeeInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
interface EmployeeRepositoryInterface
{
    /**
     * @param string $id
     *
     * @return EmployeeInterface
     */
    public function find(?string $id): ? EmployeeInterface;

    /**
     * @param array $ids
     *
     * @return EmployeeInterface[]
     */
    public function finds(array $ids = []): array;

    /**
     * @param string $jobLevelId
     *
     * @return array
     */
    public function findSupervisorByJobLevel(string $jobLevelId): array;

    /**
     * @param string $companyId
     *
     * @return array
     */
    public function findByCompany(string $companyId): array;

    /**
     * @param string $code
     *
     * @return EmployeeInterface|null
     */
    public function findByCode(string $code): ? EmployeeInterface;

    /**
     * @param Request $request
     *
     * @return array
     */
    public function search(Request $request): array;

    /**
     * @return EmployeeInterface[]
     */
    public function findAll(): array;
}
