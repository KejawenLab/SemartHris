<?php

declare(strict_types=1);

namespace KejawenLab\Application\SemartHris\Controller\Admin;

use KejawenLab\Application\SemartHris\Repository\SkillRepository;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class SkillController extends AdminController
{
    /**
     * @param string $entityClass
     * @param string $searchQuery
     * @param array  $searchableFields
     * @param null   $sortField
     * @param null   $sortDirection
     * @param null   $dqlFilter
     *
     * @return \Doctrine\ORM\QueryBuilder
     */
    protected function createSearchQueryBuilder($entityClass, $searchQuery, array $searchableFields, $sortField = null, $sortDirection = null, $dqlFilter = null)
    {
        return SkillRepository::createQueryBuilderForSearch($this->getDoctrine(), $searchQuery, $searchableFields, $sortField, $sortDirection, $dqlFilter);
    }
}
