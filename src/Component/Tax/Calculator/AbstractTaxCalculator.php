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

    public function calculate(float $ptkp): float
    {
        $previousValue = 0;
        if ($previous = $this->getPrevious()) {
            $previousValue = $this->getPrevious()->calculate($previous->maxPtkp());
            $ptkp -= $previous->maxPtkp();
        }

        return ($this->taxPercentage() * $ptkp) + $previousValue;
    }

    public function isSupportPtkp(float $ptkp): bool
    {
        if ($ptkp < $this->maxPtkp() && $ptkp >= $this->minPtkp()) {
            return true;
        }

        return false;
    }
}
