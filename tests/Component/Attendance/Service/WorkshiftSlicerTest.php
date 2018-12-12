<?php

namespace Component\Attendance\Service;

use KejawenLab\Application\SemartHris\Component\Attendance\Model\WorkshiftInterface;
use KejawenLab\Application\SemartHris\Component\Attendance\Repository\WorkshiftRepositoryInterface;
use KejawenLab\Application\SemartHris\Component\Attendance\Service\WorkshiftSlicer;
use KejawenLab\Application\SemartHris\Component\Employee\Model\EmployeeInterface;
use PHPUnit\Framework\TestCase;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class WorkshiftSlicerTest extends TestCase
{
    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $workshiftRepository;

    public function setUp()
    {
        $this->workshiftRepository = $this->getMockBuilder(WorkshiftRepositoryInterface::class)->getMock();
    }

    public function testSlicingWorkshitf()
    {
        $employee = $this->getMockBuilder(EmployeeInterface::class)->getMock();
        $employee->expects($this->atLeastOnce())->method('getId')->willReturn('UniqueId');

        $firtWorkhist = $this->getMockBuilder(WorkshiftInterface::class)->getMock();
        $firtWorkhist->expects($this->once())->method('getEmployee')->willReturn($employee);
        $firtWorkhist->expects($this->once())->method('getStartDate');
        $firtWorkhist->expects($this->once())->method('getEndDate');

        $secondWorkhist = $this->getMockBuilder(WorkshiftInterface::class)->getMock();
        $secondWorkhist->expects($this->once())->method('getEmployee')->willReturn($employee);
        $secondWorkhist->expects($this->once())->method('getStartDate');
        $secondWorkhist->expects($this->once())->method('getEndDate');

        $workshiftSlicer = new WorkshiftSlicer($this->workshiftRepository, WorkshiftInterface::class);
        $workshiftSlicer->slice($firtWorkhist, $secondWorkhist);
    }
}
