<?php

namespace KejawenLab\Application\SemartHris\Component\Company\Repository;

use KejawenLab\Application\SemartHris\Component\Company\Model\CompanyInterface;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@kejawenlab.com>
 */
interface CompanyRepositoryInterface
{
    /**
     * @param string $id
     *
     * @return CompanyInterface
     */
    public function find(string $id): ? CompanyInterface;
}
