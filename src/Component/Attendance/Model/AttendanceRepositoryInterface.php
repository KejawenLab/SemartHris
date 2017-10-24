<?php

namespace KejawenLab\Application\SemartHris\Component\Attendance\Model;

use Doctrine\ORM\QueryBuilder;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@kejawenlab.com>
 */
interface AttendanceRepositoryInterface
{
    /**
     * @param \DateTimeInterface $startDate
     * @param \DateTimeInterface $endDate
     * @param string|null        $companyId
     * @param string|null        $departmentId
     * @param string|null        $shiftmentId
     * @param array              $sorts
     *
     * @return QueryBuilder
     */
    public function getFilteredAttendance(\DateTimeInterface $startDate, \DateTimeInterface $endDate, string $companyId = null, string $departmentId = null, string $shiftmentId = null, array $sorts = []): QueryBuilder;
}
