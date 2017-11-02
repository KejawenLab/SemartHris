<?php

namespace KejawenLab\Application\SemartHris\Controller\Admin;

use KejawenLab\Application\SemartHris\Repository\OvertimeRepository;
use KejawenLab\Application\SemartHris\Util\Setting;

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
        $startDate = \DateTime::createFromFormat(Setting::get(Setting::DATE_FORMAT), $this->request->query->get('startDate', date(Setting::get(Setting::FIRST_DATE_FORMAT))));
        $endDate = \DateTime::createFromFormat(Setting::get(Setting::DATE_FORMAT), $this->request->query->get('endDate', date(Setting::get(Setting::LAST_DATE_FORMAT))));
        $companyId = $this->request->get('company');
        $departmentId = $this->request->get('department');
        $shiftmentId = $this->request->get('shiftment');

        return $this->container->get(OvertimeRepository::class)->getFilteredOvertime($startDate, $endDate, $companyId, $departmentId, $shiftmentId, [$sortField => $sortDirection]);
    }
}
