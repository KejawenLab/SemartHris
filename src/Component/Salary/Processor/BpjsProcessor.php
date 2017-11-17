<?php

namespace KejawenLab\Application\SemartHris\Component\Salary\Processor;

use KejawenLab\Application\SemartHris\Component\Employee\Model\EmployeeInterface;
use KejawenLab\Application\SemartHris\Component\Employee\Service\RiskRatioConverter;
use KejawenLab\Application\SemartHris\Component\Salary\Model\PayrollInterface;
use KejawenLab\Application\SemartHris\Component\Salary\Repository\ComponentRepositoryInterface;
use KejawenLab\Application\SemartHris\Component\Salary\Repository\PayrollRepositoryInterface;
use KejawenLab\Application\SemartHris\Util\SettingUtil;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@kejawenlab.com>
 */
class BpjsProcessor implements SalaryProcessorInterface
{
    /**
     * @var ComponentRepositoryInterface
     */
    private $componentRepository;

    /**
     * @var PayrollRepositoryInterface
     */
    private $payrollRepository;

    /**
     * @param ComponentRepositoryInterface $componentRepository
     * @param PayrollRepositoryInterface   $payrollRepository
     */
    public function __construct(ComponentRepositoryInterface $componentRepository, PayrollRepositoryInterface $payrollRepository)
    {
        $this->componentRepository = $componentRepository;
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
        $this->processJkk($payroll, $employee, $date, $fixedSalary);
        $this->processJkm($payroll, $employee, $date, $fixedSalary);
        $this->processJht($payroll, $employee, $date, $fixedSalary);
        $this->processJp($payroll, $employee, $date, $fixedSalary);
        $this->payrollRepository->update();

        return 0.0;
    }

    /**
     * @param PayrollInterface   $payroll
     * @param EmployeeInterface  $employee
     * @param \DateTimeInterface $date
     * @param float              $fixedSalary
     */
    private function processJkk(PayrollInterface $payroll, EmployeeInterface $employee, \DateTimeInterface $date, float $fixedSalary): void
    {
        $jkkComponent = $this->componentRepository->findByCode(SettingUtil::get(SettingUtil::JKK_COMPONENT_CODE));
        if (!$jkkComponent) {
            throw new \RuntimeException('JKK benefit code is not valid.');
        }

        $jkk = RiskRatioConverter::getValue($employee->getRiskRatio()) * $fixedSalary;
        $companyCost = $this->payrollRepository->createCompanyCost($payroll, $jkkComponent);
        $companyCost->setBenefitValue($jkk);

        $this->payrollRepository->storeCompanyCost($companyCost);
    }

    /**
     * @param PayrollInterface   $payroll
     * @param EmployeeInterface  $employee
     * @param \DateTimeInterface $date
     * @param float              $fixedSalary
     */
    private function processJkm(PayrollInterface $payroll, EmployeeInterface $employee, \DateTimeInterface $date, float $fixedSalary): void
    {
        $jkmComponent = $this->componentRepository->findByCode(SettingUtil::get(SettingUtil::JKM_COMPONENT_CODE));
        if (!$jkmComponent) {
            throw new \RuntimeException('JKM benefit code is not valid.');
        }

        $jkm = 0.003 * $fixedSalary;
        $companyCost = $this->payrollRepository->createCompanyCost($payroll, $jkmComponent);
        $companyCost->setBenefitValue($jkm);

        $this->payrollRepository->storeCompanyCost($companyCost);
    }

    /**
     * @param PayrollInterface   $payroll
     * @param EmployeeInterface  $employee
     * @param \DateTimeInterface $date
     * @param float              $fixedSalary
     */
    private function processJht(PayrollInterface $payroll, EmployeeInterface $employee, \DateTimeInterface $date, float $fixedSalary): void
    {
        $jhtCompany = $this->componentRepository->findByCode(SettingUtil::get(SettingUtil::JHTC_COMPONENT_CODE));
        if (!$jhtCompany) {
            throw new \RuntimeException('JHT company benefit code is not valid.');
        }

        $jhtEmployeePlus = $this->componentRepository->findByCode(SettingUtil::get(SettingUtil::JHTP_COMPONENT_CODE));
        if (!$jhtEmployeePlus) {
            throw new \RuntimeException('JHT employee plus benefit code is not valid.');
        }

        $jhtEmployeeMinus = $this->componentRepository->findByCode(SettingUtil::get(SettingUtil::JHTM_COMPONENT_CODE));
        if (!$jhtEmployeeMinus) {
            throw new \RuntimeException('JHT employee minus benefit code is not valid.');
        }

        $jhtc = 0.037 * $fixedSalary;
        $companyCost = $this->payrollRepository->createCompanyCost($payroll, $jhtCompany);
        $companyCost->setBenefitValue($jhtc);

        $jht = 0.02 * $fixedSalary;
        $plusJht = $this->payrollRepository->createPayrollDetail($payroll, $jhtEmployeePlus);
        $plusJht->setBenefitValue($jht);

        $minusJht = $this->payrollRepository->createPayrollDetail($payroll, $jhtEmployeeMinus);
        $minusJht->setBenefitValue($jht);

        $this->payrollRepository->storeCompanyCost($companyCost);
        $this->payrollRepository->storeDetail($plusJht);
        $this->payrollRepository->storeDetail($minusJht);
    }

    /**
     * @param PayrollInterface   $payroll
     * @param EmployeeInterface  $employee
     * @param \DateTimeInterface $date
     * @param float              $fixedSalary
     */
    private function processJp(PayrollInterface $payroll, EmployeeInterface $employee, \DateTimeInterface $date, float $fixedSalary): void
    {
        $jpCompany = $this->componentRepository->findByCode(SettingUtil::get(SettingUtil::JPC_COMPONENT_CODE));
        if (!$jpCompany) {
            throw new \RuntimeException('JP company benefit code is not valid.');
        }

        $jpEmployeePlus = $this->componentRepository->findByCode(SettingUtil::get(SettingUtil::JPP_COMPONENT_CODE));
        if (!$jpEmployeePlus) {
            throw new \RuntimeException('JP employee plus benefit code is not valid.');
        }

        $jpEmployeeMinus = $this->componentRepository->findByCode(SettingUtil::get(SettingUtil::JPM_COMPONENT_CODE));
        if (!$jpEmployeeMinus) {
            throw new \RuntimeException('JP employee minus benefit code is not valid.');
        }

        $jpc = 0.02 * $fixedSalary;
        $companyCost = $this->payrollRepository->createCompanyCost($payroll, $jpCompany);
        $companyCost->setBenefitValue($jpc);

        $jp = 0.01 * $fixedSalary;
        $plusJp = $this->payrollRepository->createPayrollDetail($payroll, $jpEmployeePlus);
        $plusJp->setBenefitValue($jp);

        $minusJp = $this->payrollRepository->createPayrollDetail($payroll, $jpEmployeeMinus);
        $minusJp->setBenefitValue($jp);

        $this->payrollRepository->storeCompanyCost($companyCost);
        $this->payrollRepository->storeDetail($plusJp);
        $this->payrollRepository->storeDetail($minusJp);
    }
}
