<?php

declare(strict_types=1);

namespace KejawenLab\Application\SemartHris\Component\Tax\Service;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@kejawenlab.com>
 *
 * @see https://www.online-pajak.com/id/tarif-pajak-pph-21
 */
class TaxPercentage
{
    /**
     * @param float $yearlyTakeHomePay
     *
     * @return float
     */
    public static function getValue(float $yearlyTakeHomePay): float
    {
        if (50000000 >= $yearlyTakeHomePay) {//Kurang dari 50jt
            return 0.05;
        }

        if (50000000 < $yearlyTakeHomePay && 250000000 >= $yearlyTakeHomePay) {//50jt - 250jt
            return 0.15;
        }

        if (250000000 < $yearlyTakeHomePay && 500000000 >= $yearlyTakeHomePay) {//250jt - 500jt
            return 0.25;
        }

        return 0.3; //Diatas 500jt
    }
}
