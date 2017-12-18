<?php

declare(strict_types=1);

namespace KejawenLab\Application\SemartHris\Repository;

use Doctrine\ORM\QueryBuilder;
use KejawenLab\Application\SemartHris\Component\Employee\Model\EmployeeInterface;
use KejawenLab\Application\SemartHris\Component\Overtime\Model\OvertimeInterface;
use KejawenLab\Application\SemartHris\Component\Overtime\Repository\OvertimeRepositoryInterface;
use KejawenLab\Application\SemartHris\Component\Setting\Service\Setting;
use KejawenLab\Application\SemartHris\Component\Setting\SettingKey;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class OvertimeRepository extends Repository implements OvertimeRepositoryInterface
{
    /**
     * @var Setting
     */
    private $setting;

    /**
     * @param Setting $setting
     */
    public function __construct(Setting $setting)
    {
        $this->setting = $setting;
    }

    /**
     * @param \DateTimeInterface $startDate
     * @param \DateTimeInterface $endDate
     * @param null|string        $companyId
     * @param null|string        $departmentId
     * @param null|string        $shiftmentId
     * @param null|string        $employeeId
     * @param array              $sorts
     *
     * @return QueryBuilder
     */
    public function getFilteredOvertime(\DateTimeInterface $startDate, \DateTimeInterface $endDate, ?string $companyId, ?string $departmentId, ?string $shiftmentId, ?string $employeeId, array $sorts = []): QueryBuilder
    {
        $queryBuilder = $this->entityManager->createQueryBuilder();
        $queryBuilder->select('o');
        $queryBuilder->from($this->entityClass, 'o');
        $queryBuilder->leftJoin('o.employee', 'e');
        $queryBuilder->andWhere($queryBuilder->expr()->gte('o.overtimeDate', $queryBuilder->expr()->literal($startDate->format('Y-m-d'))));
        $queryBuilder->andWhere($queryBuilder->expr()->lte('o.overtimeDate', $queryBuilder->expr()->literal($endDate->format('Y-m-d'))));

        if ($employeeId) {
            $queryBuilder->andWhere($queryBuilder->expr()->eq('e.id', $queryBuilder->expr()->literal($employeeId)));
        } else {
            if ($companyId) {
                $queryBuilder->andWhere($queryBuilder->expr()->eq('e.company', $queryBuilder->expr()->literal($companyId)));
            }

            if ($departmentId) {
                $queryBuilder->andWhere($queryBuilder->expr()->eq('e.department', $queryBuilder->expr()->literal($departmentId)));
            }

            if ($shiftmentId) {
                $queryBuilder->andWhere($queryBuilder->expr()->eq('o.shiftment', $queryBuilder->expr()->literal($shiftmentId)));
            }
        }

        if (!empty($sorts)) {
            foreach ($sorts as $field => $direction) {
                $queryBuilder->addOrderBy(sprintf('o.%s', $field), $direction);
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

    /**
     * @param EmployeeInterface  $employee
     * @param \DateTimeInterface $from
     * @param \DateTimeInterface $to
     *
     * @return array
     */
    public function getSummaryByEmployeeAndDate(EmployeeInterface $employee, \DateTimeInterface $from, \DateTimeInterface $to): array
    {
        $queryBuilder = $this->entityManager->createQueryBuilder();
        $queryBuilder->addSelect('SUM(o.calculatedValue) AS totalOvertime');
        $queryBuilder->from($this->entityClass, 'o');
        $queryBuilder->andWhere($queryBuilder->expr()->isNotNull('o.approvedBy'));
        $queryBuilder->andWhere($queryBuilder->expr()->eq('o.employee', $queryBuilder->expr()->literal($employee->getId())));
        $queryBuilder->andWhere($queryBuilder->expr()->gte('o.overtimeDate', $queryBuilder->expr()->literal($from->format($this->setting->get(SettingKey::DATE_QUERY_FORMAT)))));
        $queryBuilder->andWhere($queryBuilder->expr()->lte('o.overtimeDate', $queryBuilder->expr()->literal($to->format($this->setting->get(SettingKey::DATE_QUERY_FORMAT)))));

        return $queryBuilder->getQuery()->getOneOrNullResult();
    }
}
