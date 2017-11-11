<?php

namespace KejawenLab\Application\SemartHris\Controller\Admin;

use KejawenLab\Application\SemartHris\Repository\WorkshiftRepository;
use KejawenLab\Application\SemartHris\Util\SettingUtil;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@kejawenlab.com>
 */
class WorkshiftController extends AdminController
{
    /**
     * @param string $entityClass
     * @param string $sortDirection
     * @param null   $sortField
     * @param null   $dqlFilter
     *
     * @return array
     */
    protected function createListQueryBuilder($entityClass, $sortDirection, $sortField = null, $dqlFilter = null)
    {
        $startDate = $this->request->query->get('startDate');
        if (!$startDate) {
            $startDate = date(SettingUtil::get(SettingUtil::FIRST_DATE_FORMAT));
        }

        $endDate = $this->request->query->get('endDate');
        if (!$endDate) {
            $endDate = date(SettingUtil::get(SettingUtil::LAST_DATE_FORMAT));
        }

        $startDate = \DateTime::createFromFormat(SettingUtil::get(SettingUtil::DATE_FORMAT), $startDate);
        $endDate = \DateTime::createFromFormat(SettingUtil::get(SettingUtil::DATE_FORMAT), $endDate);
        $companyId = $this->request->get('company');
        $departmentId = $this->request->get('department');
        $shiftmentId = $this->request->get('shiftment');

        return $this->container->get(WorkshiftRepository::class)->getFilteredWorkshift($startDate, $endDate, $companyId, $departmentId, $shiftmentId, [$sortField => $sortDirection]);
    }
}
