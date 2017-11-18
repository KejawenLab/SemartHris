<?php

namespace KejawenLab\Application\SemartHris\Component\Salary\Processor;

use KejawenLab\Application\SemartHris\Component\Employee\Model\EmployeeInterface;
use KejawenLab\Application\SemartHris\Component\Salary\Model\PayrollInterface;
use KejawenLab\Application\SemartHris\Component\Salary\Repository\AttendanceSummaryRepositoryInterface;
use KejawenLab\Application\SemartHris\Component\Salary\Repository\ComponentRepositoryInterface;
use KejawenLab\Application\SemartHris\Component\Salary\Repository\PayrollRepositoryInterface;
use KejawenLab\Application\SemartHris\Util\SettingUtil;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@kejawenlab.com>
 */
class OvertimeProcessor implements SalaryProcessorInterface
{
    /**
     * @var ComponentRepositoryInterface
     */
    private $componentRepository;

    /**
     * @var AttendanceSummaryRepositoryInterface
     */
    private $attendanceSummaryRepository;

    /**
     * @var PayrollRepositoryInterface
     */
    private $payrollRepository;

    /**
     * @param ComponentRepositoryInterface         $componentRepository
     * @param AttendanceSummaryRepositoryInterface $attendanceSummaryRepository
     * @param PayrollRepositoryInterface           $payrollRepository
     */
    public function __construct(ComponentRepositoryInterface $componentRepository, AttendanceSummaryRepositoryInterface $attendanceSummaryRepository, PayrollRepositoryInterface $payrollRepository)
    {
        $this->componentRepository = $componentRepository;
        $this->attendanceSummaryRepository = $attendanceSummaryRepository;
        $this->payrollRepository = $payrollRepository;
    }

    /**
     * @param PayrollInterface   $payroll
     * @param EmployeeInterface  $employee
     * @param \DateTimeInterface $date
     * @param float              $fixedSalary
     *
     * @return float
     */
    public function process(PayrollInterface $payroll, EmployeeInterface $employee, \DateTimeInterface $date, float $fixedSalary): float
    {
        $overtimeValue = 0.0;
        if ($employee->isHaveOvertimeBenefit()) {
            $overtimeComponent = $this->componentRepository->findByCode(SettingUtil::get(SettingUtil::OVERTIME_COMPONENT_CODE));
            if (!$overtimeComponent) {
                throw new \RuntimeException('Overtime benefit code is not valid.');
            }

            $attendanceSummary = $this->attendanceSummaryRepository->findByEmployeeAndDate($employee, $date);
            if ($attendanceSummary) {
                $overtimeValue = (1 / 173) * $fixedSalary * $attendanceSummary->getTotalOvertime();

                $payrollDetail = $this->payrollRepository->createPayrollDetail($payroll, $overtimeComponent);
                $payrollDetail->setComponent($overtimeComponent);
                $payrollDetail->setBenefitValue($overtimeValue);

                $this->payrollRepository->storeDetail($payrollDetail);
                $this->payrollRepository->update();
            }
        }

        return $overtimeValue;
    }
}
