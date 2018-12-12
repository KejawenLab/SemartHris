<?php

declare(strict_types=1);

namespace KejawenLab\Application\SemartHris\Repository;

use Doctrine\ORM\QueryBuilder;
use KejawenLab\Application\SemartHris\Component\Attendance\Model\AttendanceInterface;
use KejawenLab\Application\SemartHris\Component\Attendance\Repository\AttendanceRepositoryInterface;
use KejawenLab\Application\SemartHris\Component\Employee\Model\EmployeeInterface;
use KejawenLab\Application\SemartHris\Component\Setting\Service\Setting;
use KejawenLab\Application\SemartHris\Component\Setting\SettingKey;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class AttendanceRepository extends Repository implements AttendanceRepositoryInterface
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
     * @param string|null        $companyId
     * @param string|null        $departmentId
     * @param string|null        $shiftmentId
     * @param string|null        $employeeId
     * @param array              $sorts
     *
     * @return QueryBuilder
     */
    public function getFilteredAttendance(\DateTimeInterface $startDate, \DateTimeInterface $endDate, ?string $companyId, ?string $departmentId, ?string $shiftmentId, ?string $employeeId, array $sorts = []): QueryBuilder
    {
        $queryBuilder = $this->entityManager->createQueryBuilder();
        $queryBuilder->select('a');
        $queryBuilder->from($this->entityClass, 'a');
        $queryBuilder->leftJoin('a.employee', 'e');
        $queryBuilder->andWhere($queryBuilder->expr()->gte('a.attendanceDate', $queryBuilder->expr()->literal($startDate->format('Y-m-d'))));
        $queryBuilder->andWhere($queryBuilder->expr()->lte('a.attendanceDate', $queryBuilder->expr()->literal($endDate->format('Y-m-d'))));

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
                $queryBuilder->andWhere($queryBuilder->expr()->eq('a.shiftment', $queryBuilder->expr()->literal($shiftmentId)));
            }
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
     * @return AttendanceInterface|null
     */
    public function findByEmployeeAndDate(EmployeeInterface $employee, \DateTimeInterface $date): ? AttendanceInterface
    {
        return $this->entityManager->getRepository($this->entityClass)->findOneBy(['employee' => $employee, 'attendanceDate' => $date]);
    }

    /**
     * @param \DateTimeInterface $date
     *
     * @return AttendanceInterface[]
     */
    public function findByDate(\DateTimeInterface $date): array
    {
        return $this->entityManager->getRepository($this->entityClass)->findBy(['attendanceDate' => $date]);
    }

    /**
     * @param AttendanceInterface $attendance
     */
    public function update(AttendanceInterface $attendance): void
    {
        $this->entityManager->persist($attendance);
        $this->entityManager->flush();
    }

    /**
     * @param EmployeeInterface  $employee
     * @param \DateTimeInterface $date
     *
     * @return AttendanceInterface
     */
    public function createNew(EmployeeInterface $employee, \DateTimeInterface $date): AttendanceInterface
    {
        /** @var AttendanceInterface $attendance */
        $attendance = new $this->entityClass();
        $attendance->setEmployee($employee);
        $attendance->setAttendanceDate($date);

        return $attendance;
    }

    /**
     * @param EmployeeInterface  $employee
     * @param \DateTimeInterface $from
     * @param \DateTimeInterface $to
     *
     * @return array
     *
     * @throws
     */
    public function getSummaryByEmployeeAndDate(EmployeeInterface $employee, \DateTimeInterface $from, \DateTimeInterface $to): array
    {
        $queryBuilder = $this->entityManager->createQueryBuilder();
        $queryBuilder->addSelect('COUNT(1) AS totalIn');
        $queryBuilder->addSelect('SUM(a.earlyIn) AS earlyIn');
        $queryBuilder->addSelect('SUM(a.earlyOut) AS earlyOut');
        $queryBuilder->addSelect('SUM(a.lateIn) AS lateIn');
        $queryBuilder->addSelect('SUM(a.lateOut) AS lateOut');
        $queryBuilder->from($this->entityClass, 'a');
        $queryBuilder->andWhere($queryBuilder->expr()->eq('a.absent', $queryBuilder->expr()->literal(false)));
        $queryBuilder->andWhere($queryBuilder->expr()->eq('a.employee', $queryBuilder->expr()->literal($employee->getId())));
        $queryBuilder->andWhere($queryBuilder->expr()->gte('a.attendanceDate', $queryBuilder->expr()->literal($from->format($this->setting->get(SettingKey::DATE_QUERY_FORMAT)))));
        $queryBuilder->andWhere($queryBuilder->expr()->lte('a.attendanceDate', $queryBuilder->expr()->literal($to->format($this->setting->get(SettingKey::DATE_QUERY_FORMAT)))));

        return $queryBuilder->getQuery()->getOneOrNullResult();
    }
}
