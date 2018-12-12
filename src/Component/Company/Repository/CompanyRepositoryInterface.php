<?php

declare(strict_types=1);

namespace KejawenLab\Application\SemartHris\Component\Company\Repository;

use KejawenLab\Application\SemartHris\Component\Company\Model\CompanyInterface;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
interface CompanyRepositoryInterface
{
    /**
     * @param string $id
     *
     * @return CompanyInterface
     */
    public function find(?string $id): ? CompanyInterface;

    /**
     * @return CompanyInterface[]
     */
    public function findAll(): array;
}
