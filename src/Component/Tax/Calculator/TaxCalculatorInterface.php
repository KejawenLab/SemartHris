<?php

declare(strict_types=1);

namespace KejawenLab\Application\SemartHris\Component\Tax\Calculator;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
interface TaxCalculatorInterface
{
    public function calculate(float $ptkp): float;

    public function isSupportPtkp(float $ptkp): bool;

    public function maxPtkp(): float;

    public function minPtkp(): float;

    public function getPrevious(): ? self;

    public function setPrevious(?self $taxCalculator): void;

    public function taxPercentage(): float;
}
