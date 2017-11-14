<?php

namespace KejawenLab\Application\SemartHris\Component\Salary\Processor;

use KejawenLab\Application\SemartHris\Component\Employee\Model\EmployeeInterface;
use KejawenLab\Application\SemartHris\Component\Encryptor\Encryptor;
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

        $takeHomePay = 0;
        $benefits = $this->benefitRepository->findFixedByEmployee($employee);
        foreach ($benefits as $benefit) {
            $benefitValue = $this->encryptor->decrypt($benefit->getBenefitValue(), $benefit->getBenefitKey());
            $takeHomePay += $benefitValue;

            $payrollDetail = $this->payrollRepository->createPayrollDetail($payroll, $benefit->getComponent());
            $payrollDetail->setBenefitValue($benefitValue);

            $this->payrollRepository->storeDetail($payrollDetail);
        }

        if ($employee->isHaveOvertimeBenefit()) {
            $overtimeComponent = $this->componentRepository->findByCode(SettingUtil::get(SettingUtil::OVERTIME_COMPONENT_CODE));
            if (!$overtimeComponent) {
                throw new \RuntimeException('Overtime benefit code is not valid.');
            }

            $attendanceSummary = $this->attendanceSummaryRepository->findByEmployeeAndDate($employee, $date);
            if ($attendanceSummary) {
                // 1/173 * Pendapatan Tetap * Jam
                $overtimeValue = (1 / 173) * $takeHomePay * $attendanceSummary->getTotalOvertime();
                $takeHomePay += $overtimeValue;

                $payrollDetail = $this->payrollRepository->createPayrollDetail($payroll, $overtimeComponent);
                $payrollDetail->setComponent($overtimeComponent);
                $payrollDetail->setBenefitValue($overtimeValue);

                $this->payrollRepository->storeDetail($payrollDetail);
            }
        }

        $allowances = $this->allowanceRepository->findByEmployeeAndDate($employee, $date);
        foreach ($allowances as $allowance) {
            $benefitValue = $this->encryptor->decrypt($allowance->getBenefitValue(), $allowance->getBenefitKey());
            if ($allowance->getComponent() && $allowance->getComponent()->getState() === StateType::STATE_PLUS) {
                $takeHomePay += $benefitValue;
            } else {
                $takeHomePay -= $benefitValue;
            }

            $payrollDetail = $this->payrollRepository->createPayrollDetail($payroll, $allowance->getComponent());
            $payrollDetail->setBenefitValue($benefitValue);

            $this->payrollRepository->storeDetail($payrollDetail);
        }

        $payroll->setTakeHomePay($takeHomePay);

        $this->payrollRepository->store($payroll);
        $this->payrollRepository->update();
    }
}
