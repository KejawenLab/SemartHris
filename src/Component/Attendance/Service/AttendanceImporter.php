<?php

declare(strict_types=1);

namespace KejawenLab\Application\SemartHris\Component\Attendance\Service;

use KejawenLab\Application\SemartHris\Component\Attendance\Model\AttendanceInterface;
use KejawenLab\Application\SemartHris\Component\Attendance\Repository\AttendanceRepositoryInterface;
use KejawenLab\Application\SemartHris\Component\Employee\Repository\EmployeeRepositoryInterface;
use KejawenLab\Application\SemartHris\Component\Reason\Repository\ReasonRepositoryInterface;
use KejawenLab\Application\SemartHris\Component\Setting\Service\Setting;
use KejawenLab\Application\SemartHris\Component\Setting\SettingKey;
use KejawenLab\Application\SemartHris\Util\StringUtil;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class AttendanceImporter
{
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
     * @var Setting
     */
    private $setting;

    /**
     * @var string
     */
    private $attendanceClass;

    /**
     * @param EmployeeRepositoryInterface   $employeeRepository
     * @param ReasonRepositoryInterface     $reasonRepository
     * @param AttendanceRepositoryInterface $attendanceRepository
     * @param Setting                       $setting
     * @param string                        $class
     */
    public function __construct(
        EmployeeRepositoryInterface $employeeRepository,
        ReasonRepositoryInterface $reasonRepository,
        AttendanceRepositoryInterface $attendanceRepository,
        Setting $setting,
        string $class
    ) {
        $this->employeeRepository = $employeeRepository;
        $this->reasonRepository = $reasonRepository;
        $this->attendanceRepository = $attendanceRepository;
        $this->setting = $setting;
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

            if (!$employee = $this->employeeRepository->findByCode(StringUtil::sanitize($attendance['employee_code']))) {
                continue;
            }

            $attendanceDate = \DateTime::createFromFormat($this->setting->get(SettingKey::DATE_FORMAT), StringUtil::sanitize($attendance['date']));
            /* @var AttendanceInterface $object */
            $object = $this->attendanceRepository->findByEmployeeAndDate($employee, $attendanceDate);
            if (!$object) {
                $object = new $this->attendanceClass();
                $object->setAttendanceDate($attendanceDate);
                $object->setEmployee($employee);
            }

            $object->setLateIn(-1);

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

            $this->attendanceRepository->update($object);
        }
    }
}
