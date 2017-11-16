<?php

namespace KejawenLab\Application\SemartHris\Component\Attendance\Service;

use KejawenLab\Application\SemartHris\Component\Attendance\Model\AttendanceInterface;
use KejawenLab\Application\SemartHris\Component\Attendance\Repository\AttendanceRepositoryInterface;
use KejawenLab\Application\SemartHris\Component\Employee\Repository\EmployeeRepositoryInterface;
use KejawenLab\Application\SemartHris\Component\Reason\Repository\ReasonRepositoryInterface;
use KejawenLab\Application\SemartHris\Util\SettingUtil;
use KejawenLab\Application\SemartHris\Util\StringUtil;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@kejawenlab.com>
 */
class AttendanceImporter
{
    /**
     * @var AttendanceCalculator
     */
    private $attendanceCalculator;

    /**
     * @var EmployeeRepositoryInterface
     */
    private $employeeRepository;

    /**
     * @var ReasonRepositoryInterface
     */
    private $reasonRepository;

    /**
     * @var AttendanceRepositoryInterface
     */
    private $attendanceRepository;

    /**
     * @var string
     */
    private $attendanceClass;

    /**
     * @param AttendanceCalculator          $attendanceCalculator
     * @param EmployeeRepositoryInterface   $employeeRepository
     * @param ReasonRepositoryInterface     $reasonRepository
     * @param AttendanceRepositoryInterface $attendanceRepository
     * @param string                        $class
     */
    public function __construct(
        AttendanceCalculator $attendanceCalculator,
        EmployeeRepositoryInterface $employeeRepository,
        ReasonRepositoryInterface $reasonRepository,
        AttendanceRepositoryInterface $attendanceRepository,
        string $class
    ) {
        $this->attendanceCalculator = $attendanceCalculator;
        $this->employeeRepository = $employeeRepository;
        $this->reasonRepository = $reasonRepository;
        $this->attendanceRepository = $attendanceRepository;
        $this->attendanceClass = $class;
    }

    /**
     * @param \Iterator $attendances
     *
     * @see uploads/templates/attendance.csv
     */
    public function import(\Iterator $attendances): void
    {
        foreach ($attendances as $attendance) {
            if (!(isset($attendance['employee_code']) || isset($attendance['date']))) {
                continue;
            }

            /* @var AttendanceInterface $object */
            if (!$employee = $this->employeeRepository->findByCode(StringUtil::sanitize($attendance['employee_code']))) {
                continue;
            }

            $attendanceDate = \DateTime::createFromFormat(SettingUtil::get(SettingUtil::DATE_FORMAT), StringUtil::sanitize($attendance['date']));
            $object = $this->attendanceRepository->findByEmployeeAndDate($employee, $attendanceDate);
            if (!$object) {
                $object = new $this->attendanceClass();
                $object->setAttendanceDate($attendanceDate);
                $object->setEmployee($employee);
            }

            if (!(isset($attendance['check_in']) && $attendance['check_in']) || !(isset($attendance['check_out']) && $attendance['check_out'])) {
                $object->setAbsent(true);
                if (isset($attendance['reason_code']) && $reason = $this->reasonRepository->findAbsentReasonByCode($attendance['reason_code'])) {
                    $object->setReason($reason);
                }
            } else {
                $object->setAbsent(false);
                $object->setCheckIn(\DateTime::createFromFormat('H:i', StringUtil::sanitize($attendance['check_in'])));
                $object->setCheckOut(\DateTime::createFromFormat('H:i', StringUtil::sanitize($attendance['check_out'])));
            }

            $this->attendanceCalculator->calculate($object);
            $this->attendanceRepository->update($object);
        }
    }
}
