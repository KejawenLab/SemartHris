<?php

namespace KejawenLab\Application\SemartHris\Repository;

use KejawenLab\Application\SemartHris\Component\Attendance\Model\WorkshiftInterface;
use KejawenLab\Application\SemartHris\Component\Attendance\Model\WorkshiftRepositoryInterface;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@kejawenlab.com>
 */
class WorkshiftRepository extends Repository implements WorkshiftRepositoryInterface
{
    /**
     * @param WorkshiftInterface $workshift
     */
    public function update(WorkshiftInterface $workshift): void
    {
        $this->entityManager->persist($workshift);
        $this->entityManager->flush();
    }

    /**
     * @param WorkshiftInterface $workshift
     *
     * @return WorkshiftInterface|null
     */
    public function findInterSectionWorkshift(WorkshiftInterface $workshift): ? WorkshiftInterface
    {
        $queryBuilder = $this->entityManager->createQueryBuilder();
        $queryBuilder->select('w');
        $queryBuilder->from($this->entityClass, 'w');
        $queryBuilder->andWhere($queryBuilder->expr()->eq('w.employee', $queryBuilder->expr()->literal($workshift->getEmployee()->getId())));
        $queryBuilder->andWhere($queryBuilder->expr()->lt('w.startDate', $queryBuilder->expr()->literal($workshift->getStartDate()->format('Y-m-d'))));
        $queryBuilder->andWhere($queryBuilder->expr()->gt('w.endDate', $queryBuilder->expr()->literal($workshift->getEndDate()->format('Y-m-d'))));

        return  $queryBuilder->getQuery()->getOneOrNullResult();
    }
}
