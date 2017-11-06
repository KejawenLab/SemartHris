<?php

namespace KejawenLab\Application\SemartHris\Repository;

use Doctrine\ORM\QueryBuilder;
use KejawenLab\Application\SemartHris\Component\Employee\Model\EmployeeInterface;
use KejawenLab\Application\SemartHris\Component\Overtime\Model\OvertimeInterface;
use KejawenLab\Application\SemartHris\Component\Overtime\Repository\OvertimeRepositoryInterface;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@kejawenlab.com>
 */
class OvertimeRepository extends Repository implements OvertimeRepositoryInterface
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
    public function getFilteredOvertime(\DateTimeInterface $startDate, \DateTimeInterface $endDate, string $companyId = null, string $departmentId = null, string $shiftmentId = null, array $sorts = []): QueryBuilder
    {
        $queryBuilder = $this->entityManager->createQueryBuilder();
        $queryBuilder->select('a');
        $queryBuilder->from($this->entityClass, 'a');
        $queryBuilder->leftJoin('a.employee', 'e');
        $queryBuilder->andWhere($queryBuilder->expr()->gte('a.overtimeDate', $queryBuilder->expr()->literal($startDate->format('Y-m-d'))));
        $queryBuilder->andWhere($queryBuilder->expr()->lte('a.overtimeDate', $queryBuilder->expr()->literal($endDate->format('Y-m-d'))));

        if ($companyId) {
            $queryBuilder->andWhere($queryBuilder->expr()->eq('e.company', $queryBuilder->expr()->literal($companyId)));
        }

        if ($departmentId) {
            $queryBuilder->andWhere($queryBuilder->expr()->eq('e.department', $queryBuilder->expr()->literal($departmentId)));
        }

        if ($shiftmentId) {
            $queryBuilder->andWhere($queryBuilder->expr()->eq('a.shiftment', $queryBuilder->expr()->literal($shiftmentId)));
        }

        if (!empty($sorts)) {
            foreach ($sorts as $field => $direction) {
                $queryBuilder->addOrderBy(sprintf('a.%s', $field), $direction);
            }
        }

        return  $queryBuilder;
    }

    /**
     * @param EmployeeInterface  $employee
     * @param \DateTimeInterface $date
     *
     * @return OvertimeInterface|null
     */
    public function findByEmployeeAndDate(EmployeeInterface $employee, \DateTimeInterface $date): ? OvertimeInterface
    {
        return $this->entityManager->getRepository($this->entityClass)->findOneBy(['employee' => $employee, 'overtimeDate' => $date]);
    }

    /**
     * @param OvertimeInterface $overtime
     */
    public function update(OvertimeInterface $overtime): void
    {
        $this->entityManager->persist($overtime);
        $this->entityManager->flush();
    }
}
