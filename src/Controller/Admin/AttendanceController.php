<?php

namespace KejawenLab\Application\SemartHris\Controller\Admin;

use EasyCorp\Bundle\EasyAdminBundle\Controller\AdminController;
use KejawenLab\Application\SemartHris\Repository\AttendanceRepository;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@kejawenlab.com>
 */
class AttendanceController extends AdminController
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
        $startDate = \DateTime::createFromFormat('d-m-Y', $this->request->query->get('startDate', date('01-m-Y')));
        $endDate = \DateTime::createFromFormat('d-m-Y', $this->request->query->get('endDate', date('t-m-Y')));
        $companyId = $this->request->get('company');
        $departmentId = $this->request->get('department');
        $shiftmentId = $this->request->get('shiftment');

        return $this->container->get(AttendanceRepository::class)->getFilteredAttendance($startDate, $endDate, $companyId, $departmentId, $shiftmentId, [$sortField => $sortDirection]);
    }
}
