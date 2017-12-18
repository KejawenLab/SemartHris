<?php

declare(strict_types=1);

namespace KejawenLab\Application\SemartHris\Component\Tax\Service;

use KejawenLab\Application\SemartHris\Component\Tax\Model\TaxGroupHistoryInterface;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class ValidateTaxHistory
{
    /**
     * @param TaxGroupHistoryInterface $history
     *
     * @return bool
     */
    public static function validate(TaxGroupHistoryInterface $history): bool
    {
        $count = 0;
        $employee = $history->getEmployee();

        if ($employee->getRiskRatio() === $history->getNewRiskRatio() || !$history->getNewRiskRatio()) {
            ++$count;
        }

        if ($employee->getTaxGroup() === $history->getNewTaxGroup() || !$history->getNewTaxGroup()) {
            ++$count;
        }

        if (2 === $count) {
            return false;
        }

        return true;
    }
}
