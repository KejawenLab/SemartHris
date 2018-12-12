<?php

declare(strict_types=1);

namespace KejawenLab\Application\SemartHris\Component\Tax\Service;

use KejawenLab\Application\SemartHris\Component\Employee\Model\EmployeeInterface;
use KejawenLab\Application\SemartHris\Component\Salary\Model\PayrollPeriodInterface;
use KejawenLab\Application\SemartHris\Component\Salary\Repository\ComponentRepositoryInterface;
use KejawenLab\Application\SemartHris\Component\Salary\Repository\PayrollRepositoryInterface;
use KejawenLab\Application\SemartHris\Component\Setting\Service\Setting;
use KejawenLab\Application\SemartHris\Component\Setting\SettingKey;
use KejawenLab\Application\SemartHris\Component\Tax\Processor\TaxProcessorInterface;
use KejawenLab\Application\SemartHris\Component\Tax\Repository\TaxRepositoryInterface;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class TaxProcessor
{
    /**
     * @var TaxProcessorInterface
     */
    private $taxProcessor;

    /**
     * @var TaxRepositoryInterface
     */
    private $taxRepository;

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
     * @var string
     */
    private $taxClass;

    public function __construct(
        TaxProcessorInterface $taxProcessor,
        TaxRepositoryInterface $taxRepository,
        ComponentRepositoryInterface $componentRepository,
        PayrollRepositoryInterface $payrollRepository,
        Setting $setting,
        string $taxClass
    ) {
        $this->taxProcessor = $taxProcessor;
        $this->taxRepository = $taxRepository;
        $this->componentRepository = $componentRepository;
        $this->payrollRepository = $payrollRepository;
        $this->setting = $setting;
        $this->taxClass = $taxClass;
    }

    /**
     * @param EmployeeInterface      $employee
     * @param PayrollPeriodInterface $period
     */
    public function process(EmployeeInterface $employee, PayrollPeriodInterface $period): void
    {
        $payroll = $this->payrollRepository->findPayroll($employee, $period);
        if (!$payroll) {
            throw new \InvalidArgumentException('Payroll not exist.');
        }

        $tax = $this->taxRepository->createTax($employee, $period);
        $tax->setTaxValue((string) $this->taxProcessor->process($payroll));
        $tax->setTaxable((string) $this->taxProcessor->getTaxableValue());
        $tax->setUntaxable((string) $this->taxProcessor->getUntaxableValue());

        $taxPlus = $this->componentRepository->findByCode((string) $this->setting->get(SettingKey::PPH21P_COMPONENT_CODE));
        if ($taxPlus) {
            $taxPlusBenefit = $this->payrollRepository->createPayrollDetail($payroll, $taxPlus);
            $taxPlusBenefit->setBenefitValue($tax->getTaxValue());

            $this->payrollRepository->storeDetail($taxPlusBenefit);
        }

        $taxMinus = $this->componentRepository->findByCode($this->setting->get(SettingKey::PPH21M_COMPONENT_CODE));
        if (!$taxMinus) {
            throw new \RuntimeException('Tax minus benefit code is not valid.');
        }

        $taxMinusBenefit = $this->payrollRepository->createPayrollDetail($payroll, $taxMinus);
        $taxMinusBenefit->setBenefitValue($tax->getTaxValue());

        $period->setClosed(true);
        $payroll->setPeriod($period);

        $this->taxRepository->update($tax);
        $this->payrollRepository->store($payroll);
        $this->payrollRepository->storeDetail($taxMinusBenefit);
        $this->payrollRepository->update();
    }
}
