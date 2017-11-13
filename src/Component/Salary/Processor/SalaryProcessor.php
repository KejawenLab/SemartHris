<?php

namespace KejawenLab\Application\SemartHris\Component\Salary\Processor;

use KejawenLab\Application\SemartHris\Component\Employee\Model\EmployeeInterface;
use KejawenLab\Application\SemartHris\Component\Encryptor\Encryptor;
use KejawenLab\Application\SemartHris\Component\Salary\Repository\AllowanceRepositoryInterface;
use KejawenLab\Application\SemartHris\Component\Salary\Repository\BenefitRepositoryInterface;
use KejawenLab\Application\SemartHris\Component\Salary\Repository\PayrollPeriodRepositoryInterface;
use KejawenLab\Application\SemartHris\Component\Salary\Repository\PayrollRepositoryInterface;
use KejawenLab\Application\SemartHris\Component\Salary\StateType;

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
     * @param Encryptor                        $encryptor
     * @param BenefitRepositoryInterface       $benefitRepository
     * @param AllowanceRepositoryInterface     $allowanceRepository
     * @param PayrollRepositoryInterface       $payrollRepository
     * @param PayrollPeriodRepositoryInterface $payrollPeriodRepository
     */
    public function __construct(
        Encryptor $encryptor,
        BenefitRepositoryInterface $benefitRepository,
        AllowanceRepositoryInterface $allowanceRepository,
        PayrollRepositoryInterface $payrollRepository,
        PayrollPeriodRepositoryInterface $payrollPeriodRepository
    ) {
        $this->encryptor = $encryptor;
        $this->benefitRepository = $benefitRepository;
        $this->allowanceRepository = $allowanceRepository;
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

        $payroll = $this->payrollRepository->createNew($employee, $payrollPeriod);

        $takeHomePay = 0;
        $benefits = $this->benefitRepository->findByEmployee($employee);
        foreach ($benefits as $benefit) {
            $benefitValue = $this->encryptor->decrypt($benefit->getBenefitValue(), $benefit->getBenefitKey());
            $takeHomePay += $benefitValue;

            $payrollDetail = $this->payrollRepository->createDetail($payroll);
            $payrollDetail->setComponent($benefit->getComponent());
            $payrollDetail->setBenefitValue($benefitValue);

            $this->payrollRepository->storeDetail($payrollDetail);
        }

        $allowances = $this->allowanceRepository->findByEmployeeAndDate($employee, $date);
        foreach ($allowances as $allowance) {
            $benefitValue = $this->encryptor->decrypt($allowance->getBenefitValue(), $allowance->getBenefitKey());
            if ($allowance->getComponent()->getState() === StateType::STATE_PLUS) {
                $takeHomePay += $benefitValue;
            } else {
                $takeHomePay -= $benefitValue;
            }

            $payrollDetail = $this->payrollRepository->createDetail($payroll);
            $payrollDetail->setComponent($allowance->getComponent());
            $payrollDetail->setBenefitValue($benefitValue);

            $this->payrollRepository->storeDetail($payrollDetail);
        }

        $payroll->setTakeHomePay($takeHomePay);

        $this->payrollRepository->store($payroll);
        $this->payrollRepository->update();
    }
}
