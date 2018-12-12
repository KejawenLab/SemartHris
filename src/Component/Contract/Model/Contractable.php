<?php

declare(strict_types=1);

namespace KejawenLab\Application\SemartHris\Component\Contract\Model;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
interface Contractable
{
    /**
     * @return string
     */
    public function getId(): string;

    /**
     * @return ContractInterface|null
     */
    public function getContract(): ? ContractInterface;

    /**
     * @param ContractInterface|null $contract
     */
    public function setContract(?ContractInterface $contract): void;
}
