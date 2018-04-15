<?php

declare(strict_types=1);

namespace KejawenLab\Application\SemartHris\Component\Tax\Calculator;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class FourthRateTaxCalculator extends AbstractTaxCalculator
{
    public function maxPkp(): float
    {
        return 10000000000000;
    }

    public function minPkp(): float
    {
        return 500000000;
    }

    public function taxPercentage(): float
    {
        return 0.3;
    }
}
