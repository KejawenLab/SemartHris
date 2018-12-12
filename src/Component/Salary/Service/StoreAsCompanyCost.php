<?php

declare(strict_types=1);

namespace KejawenLab\Application\SemartHris\Component\Salary\Service;

use KejawenLab\Application\SemartHris\Component\Salary\Model\PayrollDetailInterface;
use KejawenLab\Application\SemartHris\Component\Salary\Repository\PayrollRepositoryInterface;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class StoreAsCompanyCost
{
    private $payrollRepository;

    public function __construct(PayrollRepositoryInterface $payrollRepository)
    {
        $this->payrollRepository = $payrollRepository;
    }

    public function store(PayrollDetailInterface $payrollDetail): void
    {
        $companyCost = $this->payrollRepository->createCompanyCost($payrollDetail->getPayroll(), $payrollDetail->getComponent());
        $companyCost->setBenefitValue($payrollDetail->getBenefitValue());

        $this->payrollRepository->storeCompanyCost($companyCost);
        $this->payrollRepository->update();
    }
}
