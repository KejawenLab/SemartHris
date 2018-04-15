<?php

declare(strict_types=1);

namespace KejawenLab\Application\SemartHris\Component\Tax\Calculator;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
abstract class AbstractTaxCalculator implements TaxCalculatorInterface
{
    private $previous;

    public function getPrevious(): ? TaxCalculatorInterface
    {
        return $this->previous;
    }

    public function setPrevious(?TaxCalculatorInterface $taxCalculator): void
    {
        $this->previous = $taxCalculator;
    }

    public function calculate(float $pkp): float
    {
        $previousValue = 0;
        if ($previous = $this->getPrevious()) {
            $previousValue = $this->getPrevious()->calculate($previous->maxPkp());
            $pkp -= $previous->maxPkp();
        }

        return ($this->taxPercentage() * $pkp) + $previousValue;
    }

    public function isSupportPkp(float $pkp): bool
    {
        if ($pkp < $this->maxPkp() && $pkp >= $this->minPkp()) {
            return true;
        }

        return false;
    }
}
