<?php

declare(strict_types=1);

namespace KejawenLab\Application\SemartHris\Component\Tax\Service;

use KejawenLab\Application\SemartHris\Component\Tax\Model\TaxGroupHistoryInterface;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class SetNewTaxDataHistory
{
    /**
     * @param TaxGroupHistoryInterface $history
     */
    public static function setNewTaxData(TaxGroupHistoryInterface $history): void
    {
        $employee = $history->getEmployee();
        $taxGroup = $history->getNewTaxGroup() ?? $employee->getTaxGroup();
        $riskRatio = $history->getNewRiskRatio() ?? $employee->getRiskRatio();

        $employee->setTaxGroup($taxGroup);
        $employee->setRiskRatio($riskRatio);
    }
}
