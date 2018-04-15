<?php

declare(strict_types=1);

namespace KejawenLab\Application\SemartHris\Component\Tax\Calculator;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class FirstRateTaxCalculator extends AbstractTaxCalculator
{
    public function maxPkp(): float
    {
        return 50000000;
    }

    public function minPkp(): float
    {
        return 0;
    }

    public function taxPercentage(): float
    {
        return 0.05;
    }
}
