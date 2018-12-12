<?php

declare(strict_types=1);

namespace KejawenLab\Application\SemartHris\Component\Tax\Calculator;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
interface TaxCalculatorInterface
{
    public function calculate(float $pkp): float;

    public function isSupportPkp(float $pkp): bool;

    public function maxPkp(): float;

    public function minPkp(): float;

    public function getPrevious(): ? self;

    public function setPrevious(?self $taxCalculator): void;

    public function taxPercentage(): float;
}
