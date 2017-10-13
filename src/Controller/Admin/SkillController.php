<?php

namespace KejawenLab\Application\SemartHris\Controller\Admin;

use EasyCorp\Bundle\EasyAdminBundle\Controller\AdminController;
use KejawenLab\Application\SemartHris\Repository\SkillRepository;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@kejawenlab.com>
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
