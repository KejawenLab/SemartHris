<?php

declare(strict_types=1);

namespace KejawenLab\Application\SemartHris\Component\Salary\Repository;

use KejawenLab\Application\SemartHris\Component\Salary\Model\ComponentInterface;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
interface ComponentRepositoryInterface
{
    /**
     * @param string $id
     *
     * @return ComponentInterface|null
     */
    public function find(?string $id): ? ComponentInterface;

    /**
     * @param string $code
     *
     * @return ComponentInterface|null
     */
    public function findByCode(string $code): ? ComponentInterface;

    /**
     * @return ComponentInterface[]
     */
    public function findFixed(): array;

    /**
     * @param string $state
     *
     * @return ComponentInterface[]
     */
    public function findByState(string $state): array;
}
