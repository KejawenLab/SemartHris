<?php

namespace KejawenLab\Application\SemartHris\Component\Salary\Processor;

use KejawenLab\Application\SemartHris\Component\Employee\Model\EmployeeInterface;
use KejawenLab\Application\SemartHris\Component\Encryptor\Encryptor;
use KejawenLab\Application\SemartHris\Component\Salary\Model\PayrollInterface;
use KejawenLab\Application\SemartHris\Component\Salary\Repository\AllowanceRepositoryInterface;
use KejawenLab\Application\SemartHris\Component\Salary\Repository\AttendanceSummaryRepositoryInterface;
use KejawenLab\Application\SemartHris\Component\Salary\Repository\BenefitRepositoryInterface;
use KejawenLab\Application\SemartHris\Component\Salary\Repository\ComponentRepositoryInterface;
use KejawenLab\Application\SemartHris\Component\Salary\Repository\PayrollPeriodRepositoryInterface;
use KejawenLab\Application\SemartHris\Component\Salary\Repository\PayrollRepositoryInterface;
use KejawenLab\Application\SemartHris\Component\Salary\StateType;
use KejawenLab\Application\SemartHris\Util\SettingUtil;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@kejawenlab.com>
 */
class SalaryProcessor implements ProcessorInterface
{
    /**
     * @var Encryptor
     */
    private $encryptor;

    /**
     * @var ComponentRepositoryInterface
     */
    private $componentRepository;

    /**
     * @var BenefitRepositoryInterface
     */
    private $benefitRepository;

    /**
     * @var AllowanceRepositoryInterface
     */
    private $allowanceRepository;

    /**
     * @var AttendanceSummaryRepositoryInterface
     */
    private $attendanceSummaryRepository;

    /**
     * @var PayrollRepositoryInterface
     */
    private $payrollRepository;

    /**
     * @var PayrollPeriodRepositoryInterface
     */
    private $payrollPeriodRepository;

    /**
     * @param Encryptor                            $encryptor
     * @param ComponentRepositoryInterface         $componentRepository
     * @param BenefitRepositoryInterface           $benefitRepository
     * @param AllowanceRepositoryInterface         $allowanceRepository
     * @param AttendanceSummaryRepositoryInterface $attendanceSummaryRepository
     * @param PayrollRepositoryInterface           $payrollRepository
     * @param PayrollPeriodRepositoryInterface     $payrollPeriodRepository
     */
    public function __construct(
        Encryptor $encryptor,
        ComponentRepositoryInterface $componentRepository,
        BenefitRepositoryInterface $benefitRepository,
        AllowanceRepositoryInterface $allowanceRepository,
        AttendanceSummaryRepositoryInterface $attendanceSummaryRepository,
        PayrollRepositoryInterface $payrollRepository,
        PayrollPeriodRepositoryInterface $payrollPeriodRepository
    ) {
        $this->encryptor = $encryptor;
        $this->componentRepository = $componentRepository;
        $this->benefitRepository = $benefitRepository;
        $this->allowanceRepository = $allowanceRepository;
        $this->attendanceSummaryRepository = $attendanceSummaryRepository;
        $this->payrollRepository = $payrollRepository;
        $this->payrollPeriodRepository = $payrollPeriodRepository;
    }

    /**
     * @param EmployeeInterface  $employee
     * @param \DateTimeInterface $date
     */
    public function process(EmployeeInterface $employee, \DateTimeInterface $date): void
    {
        $payrollPeriod = $this->payrollPeriodRepository->findByEmployeeAndDate($employee, $date);
        if (!$payrollPeriod || $payrollPeriod->isClosed()) {
            throw new InvalidPayrollPeriodException($date);
        }

        $payroll = $this->payrollRepository->createPayroll($employee, $payrollPeriod);

        $takeHomePay = $this->processOvertime($employee, $date, $payroll, $this->processFixedBenefit($employee, $payroll));
        $takeHomePay += $this->processAllowance($employee, $date, $payroll);

        $payroll->setTakeHomePay($takeHomePay);
        $this->payrollRepository->store($payroll);
        $this->payrollRepository->update();
    }

    /**
     * @param EmployeeInterface $employee
     * @param PayrollInterface  $payroll
     *
     * @return float
     */
    private function processFixedBenefit(EmployeeInterface $employee, PayrollInterface $payroll): float
    {
        $totalBenefit = 0;
        $benefits = $this->benefitRepository->findFixedByEmployee($employee);
        foreach ($benefits as $benefit) {
            $benefitValue = $this->encryptor->decrypt($benefit->getBenefitValue(), $benefit->getBenefitKey());
            $totalBenefit += $benefitValue;

            $payrollDetail = $this->payrollRepository->createPayrollDetail($payroll, $benefit->getComponent());
            $payrollDetail->setBenefitValue($benefitValue);

            $this->payrollRepository->storeDetail($payrollDetail);
        }

        return (float) $totalBenefit;
    }

    /**
     * @param EmployeeInterface  $employee
     * @param \DateTimeInterface $date
     * @param PayrollInterface   $payroll
     *
     * @return float
     */
    private function processAllowance(EmployeeInterface $employee, \DateTimeInterface $date, PayrollInterface $payroll): float
    {
        $totalAllowance = 0;
        $allowances = $this->allowanceRepository->findByEmployeeAndDate($employee, $date);
        foreach ($allowances as $allowance) {
            $benefitValue = $this->encryptor->decrypt($allowance->getBenefitValue(), $allowance->getBenefitKey());
            if ($allowance->getComponent() && $allowance->getComponent()->getState() === StateType::STATE_PLUS) {
                $totalAllowance += $benefitValue;
            } else {
                $totalAllowance -= $benefitValue;
            }

            $payrollDetail = $this->payrollRepository->createPayrollDetail($payroll, $allowance->getComponent());
            $payrollDetail->setBenefitValue($benefitValue);

            $this->payrollRepository->storeDetail($payrollDetail);
        }

        return (float) $totalAllowance;
    }

    /**
     * @param EmployeeInterface  $employee
     * @param \DateTimeInterface $date
     * @param PayrollInterface   $payroll
     * @param float              $fixedSalary
     *
     * @return float
     */
    public function processOvertime(EmployeeInterface $employee, \DateTimeInterface $date, PayrollInterface $payroll, float $fixedSalary): float
    {
        if ($employee->isHaveOvertimeBenefit()) {
            $overtimeComponent = $this->componentRepository->findByCode(SettingUtil::get(SettingUtil::OVERTIME_COMPONENT_CODE));
            if (!$overtimeComponent) {
                throw new \RuntimeException('Overtime benefit code is not valid.');
            }

            $attendanceSummary = $this->attendanceSummaryRepository->findByEmployeeAndDate($employee, $date);
            if ($attendanceSummary) {
                // 1/173 * Pendapatan Tetap * Jam
                $overtimeValue = (1 / 173) * $fixedSalary * $attendanceSummary->getTotalOvertime();
                $fixedSalary += $overtimeValue;

                $payrollDetail = $this->payrollRepository->createPayrollDetail($payroll, $overtimeComponent);
                $payrollDetail->setComponent($overtimeComponent);
                $payrollDetail->setBenefitValue($overtimeValue);

                $this->payrollRepository->storeDetail($payrollDetail);
            }
        }

        return $fixedSalary;
    }
}
