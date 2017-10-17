<?php

namespace KejawenLab\Application\SemartHris\Component\Contract\Model;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@kejawenlab.com>
 */
interface ContractInterface
{
    /**
     * @return string
     */
    public function getId(): string;

    /**
     * @return string
     */
    public function getType(): string;

    /**
     * @return string
     */
    public function getLetterNumber(): string;

    /**
     * @return string
     */
    public function getDescription(): string;

    /**
     * @return \DateTimeInterface
     */
    public function getStartDate(): \DateTimeInterface;

    /**
     * @return \DateTimeInterface|null
     */
    public function getEndDate(): ? \DateTimeInterface;

    /**
     * Useful for quick search.
     *
     * @return array
     */
    public function getTags(): array;

    /**
     * @return Contractable
     */
    public function getContractable(): Contractable;
}
