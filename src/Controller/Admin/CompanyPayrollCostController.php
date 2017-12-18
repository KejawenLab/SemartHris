<?php

declare(strict_types=1);

namespace KejawenLab\Application\SemartHris\Controller\Admin;

use Doctrine\ORM\QueryBuilder;
use KejawenLab\Application\SemartHris\Repository\CompanyPayrollCostRepository;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class CompanyPayrollCostController extends AdminController
{
    /**
     * @param string $entityClass
     * @param string $sortDirection
     * @param null   $sortField
     * @param null   $dqlFilter
     *
     * @return QueryBuilder
     */
    protected function createListQueryBuilder($entityClass, $sortDirection, $sortField = null, $dqlFilter = null)
    {
        return CompanyPayrollCostRepository::createListQueryBuilder($this->request, $this->getDoctrine(), $sortField, $sortDirection, $dqlFilter);
    }
}
