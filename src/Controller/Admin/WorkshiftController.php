<?php

declare(strict_types=1);

namespace KejawenLab\Application\SemartHris\Controller\Admin;

use KejawenLab\Application\SemartHris\Component\Setting\Service\Setting;
use KejawenLab\Application\SemartHris\Component\Setting\SettingKey;
use KejawenLab\Application\SemartHris\Repository\WorkshiftRepository;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
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
        $setting = $this->container->get(Setting::class);
        $startDate = $this->request->query->get('startDate');
        if (!$startDate) {
            $startDate = date($setting->get(SettingKey::FIRST_DATE_FORMAT));
        }

        $endDate = $this->request->query->get('endDate');
        if (!$endDate) {
            $endDate = date($setting->get(SettingKey::LAST_DATE_FORMAT));
        }

        $startDate = \DateTime::createFromFormat($setting->get(SettingKey::DATE_FORMAT), $startDate);
        $endDate = \DateTime::createFromFormat($setting->get(SettingKey::DATE_FORMAT), $endDate);
        $companyId = $this->request->get('company');
        $departmentId = $this->request->get('department');
        $shiftmentId = $this->request->get('shiftment');

        return $this->container->get(WorkshiftRepository::class)->getFilteredWorkshift($startDate, $endDate, $companyId, $departmentId, $shiftmentId, [$sortField => $sortDirection]);
    }
}
