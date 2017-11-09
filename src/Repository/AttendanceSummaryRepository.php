<?php

namespace KejawenLab\Application\SemartHris\Repository;

use KejawenLab\Application\SemartHris\Component\Employee\Model\EmployeeInterface;
use KejawenLab\Application\SemartHris\Component\Salary\Model\AttendanceSummaryInterface;
use KejawenLab\Application\SemartHris\Component\Salary\Repository\AttendanceSummaryRepositoryInterface;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@kejawenlab.com>
 */
class AttendanceSummaryRepository extends Repository implements AttendanceSummaryRepositoryInterface
{
    /**
     * @param EmployeeInterface  $employee
     * @param \DateTimeInterface $date
     *
     * @return AttendanceSummaryInterface|null
     */
    public function findByEmployeeAndDate(EmployeeInterface $employee, \DateTimeInterface $date): ? AttendanceSummaryInterface
    {
        return $this->entityManager->getRepository($this->entityClass)->findOneBy([
            'employee' => $employee,
            'year' => $date->format('Y'),
            'month' => $date->format('n'),
        ]);
    }

    /**
     * @param AttendanceSummaryInterface $summary
     */
    public function update(AttendanceSummaryInterface $summary): void
    {
        $manager = $this->entityManager;
        $manager->persist($summary);
        $manager->flush();
    }
}
