<?php

declare(strict_types=1);

namespace KejawenLab\Application\SemartHris\Component\Salary\Processor;

use KejawenLab\Application\SemartHris\Component\Employee\Model\EmployeeInterface;
use KejawenLab\Application\SemartHris\Component\Salary\Model\PayrollInterface;
use KejawenLab\Application\SemartHris\Component\Salary\Repository\AttendanceSummaryRepositoryInterface;
use KejawenLab\Application\SemartHris\Component\Salary\Repository\ComponentRepositoryInterface;
use KejawenLab\Application\SemartHris\Component\Salary\Repository\PayrollRepositoryInterface;
use KejawenLab\Application\SemartHris\Component\Setting\Service\Setting;
use KejawenLab\Application\SemartHris\Component\Setting\SettingKey;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
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
     * @var Setting
     */
    private $setting;

    /**
     * @param ComponentRepositoryInterface         $componentRepository
     * @param AttendanceSummaryRepositoryInterface $attendanceSummaryRepository
     * @param PayrollRepositoryInterface           $payrollRepository
     * @param Setting                              $setting
     */
    public function __construct(
        ComponentRepositoryInterface $componentRepository,
        AttendanceSummaryRepositoryInterface $attendanceSummaryRepository,
        PayrollRepositoryInterface $payrollRepository,
        Setting $setting
    ) {
        $this->componentRepository = $componentRepository;
        $this->attendanceSummaryRepository = $attendanceSummaryRepository;
        $this->payrollRepository = $payrollRepository;
        $this->setting = $setting;
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
            $overtimeComponent = $this->componentRepository->findByCode($this->setting->get(SettingKey::OVERTIME_COMPONENT_CODE));
            if (!$overtimeComponent) {
                throw new \RuntimeException('Overtime benefit code is not valid.');
            }

            $attendanceSummary = $this->attendanceSummaryRepository->findByEmployeeAndDate($employee, $date);
            if ($attendanceSummary) {
                $overtimeValue = (1 / 173) * $fixedSalary * $attendanceSummary->getTotalOvertime();
                $overtimeValue = round($overtimeValue, 0, PHP_ROUND_HALF_DOWN);

                $payrollDetail = $this->payrollRepository->createPayrollDetail($payroll, $overtimeComponent);
                $payrollDetail->setComponent($overtimeComponent);
                $payrollDetail->setBenefitValue((string) $overtimeValue);

                $this->payrollRepository->storeDetail($payrollDetail);
                $this->payrollRepository->update();
            }
        }

        return $overtimeValue;
    }
}
