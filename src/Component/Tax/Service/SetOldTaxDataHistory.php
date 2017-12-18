<?php

declare(strict_types=1);

namespace KejawenLab\Application\SemartHris\Component\Tax\Service;

use KejawenLab\Application\SemartHris\Component\Tax\Model\TaxGroupHistoryInterface;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class SetOldTaxDataHistory
{
    /**
     * @param TaxGroupHistoryInterface $history
     */
    public static function setOldTaxData(TaxGroupHistoryInterface $history): void
    {
        $employee = $history->getEmployee();
        $history->setOldTaxGroup($employee->getTaxGroup());
        $history->setOldRiskRatio($employee->getRiskRatio());
    }
}
