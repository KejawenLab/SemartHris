<?php

declare(strict_types=1);

namespace KejawenLab\Application\SemartHris\Component\Salary\Processor;

use KejawenLab\Application\SemartHris\Component\Employee\Model\EmployeeInterface;
use KejawenLab\Application\SemartHris\Component\Employee\Service\RiskRatioConverter;
use KejawenLab\Application\SemartHris\Component\Salary\Model\PayrollInterface;
use KejawenLab\Application\SemartHris\Component\Salary\Repository\ComponentRepositoryInterface;
use KejawenLab\Application\SemartHris\Component\Salary\Repository\PayrollRepositoryInterface;
use KejawenLab\Application\SemartHris\Component\Setting\Service\Setting;
use KejawenLab\Application\SemartHris\Component\Setting\SettingKey;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 *
 * @see http://www.pasienbpjs.com/2017/01/cara-menghitung-iuran-bpjs-ketenagakerjaan.html
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
     * @var Setting
     */
    private $setting;

    /**
     * @param ComponentRepositoryInterface $componentRepository
     * @param PayrollRepositoryInterface   $payrollRepository
     * @param Setting                      $setting
     */
    public function __construct(
        ComponentRepositoryInterface $componentRepository,
        PayrollRepositoryInterface $payrollRepository,
        Setting $setting
    ) {
        $this->componentRepository = $componentRepository;
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
        $jkkComponent = $this->componentRepository->findByCode($this->setting->get(SettingKey::JKK_COMPONENT_CODE));
        if (!$jkkComponent) {
            throw new \RuntimeException('JKK benefit code is not valid.');
        }

        $jkk = RiskRatioConverter::getValue($employee->getRiskRatio()) * $fixedSalary;
        $jkk = round($jkk, 0, PHP_ROUND_HALF_DOWN);
        $companyCost = $this->payrollRepository->createCompanyCost($payroll, $jkkComponent);
        $companyCost->setBenefitValue((string) $jkk);

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
        $jkmComponent = $this->componentRepository->findByCode($this->setting->get(SettingKey::JKM_COMPONENT_CODE));
        if (!$jkmComponent) {
            throw new \RuntimeException('JKM benefit code is not valid.');
        }

        $jkm = round(0.003 * $fixedSalary, 0, PHP_ROUND_HALF_DOWN);
        $companyCost = $this->payrollRepository->createCompanyCost($payroll, $jkmComponent);
        $companyCost->setBenefitValue((string) $jkm);

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
        $jhtCompany = $this->componentRepository->findByCode($this->setting->get(SettingKey::JHTC_COMPONENT_CODE));
        if (!$jhtCompany) {
            throw new \RuntimeException('JHT company benefit code is not valid.');
        }

        $jhtc = round(0.037 * $fixedSalary, 0, PHP_ROUND_HALF_DOWN);
        $jht = round(0.02 * $fixedSalary, 0, PHP_ROUND_HALF_DOWN);

        $companyCost = $this->payrollRepository->createCompanyCost($payroll, $jhtCompany);
        $companyCost->setBenefitValue((string) $jhtc);

        $this->payrollRepository->storeCompanyCost($companyCost);

        $jhtEmployeePlus = $this->componentRepository->findByCode((string) $this->setting->get(SettingKey::JHTP_COMPONENT_CODE));
        if ($jhtEmployeePlus) {
            $plusJht = $this->payrollRepository->createPayrollDetail($payroll, $jhtEmployeePlus);
            $plusJht->setBenefitValue((string) $jht);

            $this->payrollRepository->storeDetail($plusJht);
        }

        $jhtEmployeeMinus = $this->componentRepository->findByCode((string) $this->setting->get(SettingKey::JHTM_COMPONENT_CODE));
        if ($jhtEmployeeMinus) {
            $minusJht = $this->payrollRepository->createPayrollDetail($payroll, $jhtEmployeeMinus);
            $minusJht->setBenefitValue((string) $jht);

            $this->payrollRepository->storeDetail($minusJht);
        }
    }

    /**
     * @param PayrollInterface   $payroll
     * @param EmployeeInterface  $employee
     * @param \DateTimeInterface $date
     * @param float              $fixedSalary
     */
    private function processJp(PayrollInterface $payroll, EmployeeInterface $employee, \DateTimeInterface $date, float $fixedSalary): void
    {
        $jpCompany = $this->componentRepository->findByCode($this->setting->get(SettingKey::JPC_COMPONENT_CODE));
        if (!$jpCompany) {
            throw new \RuntimeException('JP company benefit code is not valid.');
        }

        $jpc = round(0.02 * $fixedSalary, 0, PHP_ROUND_HALF_DOWN);
        $jp = round(0.01 * $fixedSalary, 0, PHP_ROUND_HALF_DOWN);

        $companyCost = $this->payrollRepository->createCompanyCost($payroll, $jpCompany);
        $companyCost->setBenefitValue((string) $jpc);

        $this->payrollRepository->storeCompanyCost($companyCost);

        $jpEmployeePlus = $this->componentRepository->findByCode((string) $this->setting->get(SettingKey::JPP_COMPONENT_CODE));
        if ($jpEmployeePlus) {
            $plusJp = $this->payrollRepository->createPayrollDetail($payroll, $jpEmployeePlus);
            $plusJp->setBenefitValue((string) $jp);

            $this->payrollRepository->storeDetail($plusJp);
        }

        $jpEmployeeMinus = $this->componentRepository->findByCode((string) $this->setting->get(SettingKey::JPM_COMPONENT_CODE));
        if ($jpEmployeeMinus) {
            $minusJp = $this->payrollRepository->createPayrollDetail($payroll, $jpEmployeeMinus);
            $minusJp->setBenefitValue((string) $jp);

            $this->payrollRepository->storeDetail($minusJp);
        }
    }
}
