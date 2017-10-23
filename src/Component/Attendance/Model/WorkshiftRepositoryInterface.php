<?php

namespace KejawenLab\Application\SemartHris\Component\Attendance\Model;

use Doctrine\ORM\QueryBuilder;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@kejawenlab.com>
 */
interface WorkshiftRepositoryInterface
{
    /**
     * @param WorkshiftInterface $workshift
     */
    public function update(WorkshiftInterface $workshift): void;

    /**
     * @param WorkshiftInterface $workshift
     *
     * @return WorkshiftInterface|null
     */
    public function findInterSectionWorkshift(WorkshiftInterface $workshift): ? WorkshiftInterface;

    /**
     * @param \DateTimeInterface $startDate
     * @param \DateTimeInterface $endDate
     * @param string|null        $companyId
     * @param string|null        $departmentId
     * @param string|null        $shiftmentId
     *
     * @return QueryBuilder
     */
    public function getWorkshiftFiltered(\DateTimeInterface $startDate, \DateTimeInterface $endDate, string $companyId = null, string $departmentId = null, string $shiftmentId = null): QueryBuilder;
}
