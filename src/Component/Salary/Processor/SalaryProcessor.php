<?php

declare(strict_types=1);

namespace KejawenLab\Application\SemartHris\Component\Salary\Processor;

use KejawenLab\Application\SemartHris\Component\Employee\Model\EmployeeInterface;
use KejawenLab\Application\SemartHris\Component\Encryptor\Encryptor;
use KejawenLab\Application\SemartHris\Component\Salary\Model\PayrollInterface;
use KejawenLab\Application\SemartHris\Component\Salary\Repository\AllowanceRepositoryInterface;
use KejawenLab\Application\SemartHris\Component\Salary\Repository\BenefitRepositoryInterface;
use KejawenLab\Application\SemartHris\Component\Salary\Repository\ComponentRepositoryInterface;
use KejawenLab\Application\SemartHris\Component\Salary\Repository\PayrollPeriodRepositoryInterface;
use KejawenLab\Application\SemartHris\Component\Salary\Repository\PayrollRepositoryInterface;
use KejawenLab\Application\SemartHris\Component\Salary\StateType;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class SalaryProcessor implements PayrollProcessorInterface
{
    const SEMARTHRIS_SALARY_PROCESSOR = 'semarthris.salary_processor';

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
     * @var PayrollRepositoryInterface
     */
    private $payrollRepository;

    /**
     * @var PayrollPeriodRepositoryInterface
     */
    private $payrollPeriodRepository;

    /**
     * @var SalaryProcessorInterface[]
     */
    private $processors;

    /**
     * @param Encryptor                        $encryptor
     * @param ComponentRepositoryInterface     $componentRepository
     * @param BenefitRepositoryInterface       $benefitRepository
     * @param AllowanceRepositoryInterface     $allowanceRepository
     * @param PayrollRepositoryInterface       $payrollRepository
     * @param PayrollPeriodRepositoryInterface $payrollPeriodRepository
     * @param SalaryProcessorInterface[]       $processors
     */
    public function __construct(
        Encryptor $encryptor,
        ComponentRepositoryInterface $componentRepository,
        BenefitRepositoryInterface $benefitRepository,
        AllowanceRepositoryInterface $allowanceRepository,
        PayrollRepositoryInterface $payrollRepository,
        PayrollPeriodRepositoryInterface $payrollPeriodRepository,
        array $processors = []
    ) {
        $this->encryptor = $encryptor;
        $this->componentRepository = $componentRepository;
        $this->benefitRepository = $benefitRepository;
        $this->allowanceRepository = $allowanceRepository;
        $this->payrollRepository = $payrollRepository;
        $this->payrollPeriodRepository = $payrollPeriodRepository;

        $this->processors = [];
        foreach ($processors as $processor) {
            $this->addProcessor($processor);
        }
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
        $fixedSalary = $this->processFixedBenefit($employee, $payroll);

        $takeHomePay = $fixedSalary;
        foreach ($this->processors as $processor) {
            $takeHomePay += $processor->process($payroll, $employee, $date, $fixedSalary);
        }
        $takeHomePay += $this->processAllowance($employee, $date, $payroll);

        $payroll->setTakeHomePay((string) $takeHomePay);
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
            if ($allowance->getComponent() && StateType::STATE_PLUS === $allowance->getComponent()->getState()) {
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
     * @param SalaryProcessorInterface $processor
     */
    private function addProcessor(SalaryProcessorInterface $processor): void
    {
        $this->processors[] = $processor;
    }
}
