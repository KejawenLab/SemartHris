<?php

namespace KejawenLab\Application\SemartHris\Controller\Admin;

use KejawenLab\Application\SemartHris\Repository\OvertimeRepository;
use KejawenLab\Application\SemartHris\Util\SettingUtil;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@kejawenlab.com>
 */
class OvertimeController extends AdminController
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
        $startDate = \DateTime::createFromFormat(SettingUtil::get(SettingUtil::DATE_FORMAT), $this->request->query->get('startDate', date(SettingUtil::get(SettingUtil::FIRST_DATE_FORMAT))));
        $endDate = \DateTime::createFromFormat(SettingUtil::get(SettingUtil::DATE_FORMAT), $this->request->query->get('endDate', date(SettingUtil::get(SettingUtil::LAST_DATE_FORMAT))));
        $companyId = $this->request->get('company');
        $departmentId = $this->request->get('department');
        $shiftmentId = $this->request->get('shiftment');

        return $this->container->get(OvertimeRepository::class)->getFilteredOvertime($startDate, $endDate, $companyId, $departmentId, $shiftmentId, [$sortField => $sortDirection]);
    }
}
