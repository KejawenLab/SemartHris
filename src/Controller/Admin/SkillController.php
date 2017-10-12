<?php

namespace KejawenLab\Application\SemarHris\Controller\Admin;

use EasyCorp\Bundle\EasyAdminBundle\Controller\AdminController;
use KejawenLab\Application\SemarHris\Repository\SkillRepository;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@kejawenlab.com>
 */
class SkillController extends AdminController
{
    protected function createSearchQueryBuilder($entityClass, $searchQuery, array $searchableFields, $sortField = null, $sortDirection = null, $dqlFilter = null)
    {
        return SkillRepository::createQueryBuilderForSearch($this->getDoctrine(), $searchQuery, $searchableFields, $sortField, $sortDirection, $dqlFilter);
    }
}
