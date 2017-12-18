<?php

namespace Tests\KejawenLab\Application\SemartHris\Component\Attendance\Service;

use KejawenLab\Application\SemartHris\Component\Attendance\Model\AttendanceInterface;
use KejawenLab\Application\SemartHris\Component\Attendance\Repository\AttendanceRepositoryInterface;
use KejawenLab\Application\SemartHris\Component\Attendance\Service\AttendanceImporter;
use KejawenLab\Application\SemartHris\Component\Employee\Model\EmployeeInterface;
use KejawenLab\Application\SemartHris\Component\Employee\Repository\EmployeeRepositoryInterface;
use KejawenLab\Application\SemartHris\Component\Reason\Repository\ReasonRepositoryInterface;
use KejawenLab\Application\SemartHris\Component\Setting\Service\Setting;
use KejawenLab\Application\SemartHris\Component\Setting\SettingKey;
use PHPUnit\Framework\TestCase;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class AttendanceImporterTest extends TestCase
{
    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $employeeRepository;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $reasonRepository;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $attendanceRepository;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $setting;

    /**
     * @var \ArrayIterator
     */
    private $fakeData;

    public function setUp()
    {
        $this->fakeData = new \ArrayIterator([
            [
                'employee_code' => 'TEST',
                'date' => '01-11-2017',
                'check_in' => '08:00',
                'check_out' => '17:00',
                'reason_code' => null,
            ],
            [
                'employee_code' => 'TEST',
                'date' => '02-11-2017',
                'check_in' => '08:00',
                'check_out' => '17:00',
                'reason_code' => null,
            ],
            [
                'employee_code' => 'TEST',
                'date' => '03-11-2017',
                'check_in' => null,
                'check_out' => null,
                'reason_code' => 'ABS',
            ],
            [
                'employee_code' => 'TEST',
                'date' => '04-11-2017',
                'check_in' => '08:00',
                'check_out' => '17:00',
                'reason_code' => null,
            ],
            [
                'employee_code' => 'TEST',
                'date' => '05-11-2017',
                'check_in' => '08:00',
                'check_out' => '17:00',
                'reason_code' => null,
            ],
        ]);

        $this->employeeRepository = $this->getMockBuilder(EmployeeRepositoryInterface::class)->getMock();
        $employee = $this->getMockBuilder(EmployeeInterface::class)->getMock();
        $this->employeeRepository->expects($this->atLeast(5))->method('findByCode')->willReturn($employee);

        $this->reasonRepository = $this->getMockBuilder(ReasonRepositoryInterface::class)->getMock();
        $this->reasonRepository->expects($this->once())->method('findAbsentReasonByCode');
        $this->attendanceRepository = $this->getMockBuilder(AttendanceRepositoryInterface::class)->getMock();
        $this->setting = $this->getMockBuilder(Setting::class)->disableOriginalConstructor()->getMock();
        $this->setting->expects($this->atLeastOnce())->method('get')->withAnyParameters()->willReturn('d-m-Y');
    }

    public function testImportUseExistingAttendance()
    {
        $attendance = $this->getMockBuilder(AttendanceInterface::class)->getMock();
        $this->attendanceRepository->expects($this->atLeast(5))->method('findByEmployeeAndDate')->willReturn($attendance);

        $importer = new AttendanceImporter($this->employeeRepository, $this->reasonRepository, $this->attendanceRepository, $this->setting, AttendanceStub::class);

        $importer->import($this->fakeData);
    }

    public function testImportUseNewAttendance()
    {
        $this->attendanceRepository->expects($this->atLeast(5))->method('findByEmployeeAndDate')->willReturn(null);

        $importer = new AttendanceImporter($this->employeeRepository, $this->reasonRepository, $this->attendanceRepository, $this->setting, AttendanceStub::class);

        $importer->import($this->fakeData);
    }
}
