<?php

declare(strict_types=1);

namespace KejawenLab\Application\SemartHris\Component\Tax\Calculator;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class SecondRateTaxCalculator extends AbstractTaxCalculator
{
    public function maxPkp(): float
    {
        return 250000000;
    }

    public function minPkp(): float
    {
        return 50000000;
    }

    public function taxPercentage(): float
    {
        return 0.15;
    }
}
