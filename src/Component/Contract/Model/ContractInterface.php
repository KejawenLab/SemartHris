<?php

namespace KejawenLab\Application\SemartHris\Component\Contract\Model;

use KejawenLab\Application\SemartHris\Component\Contract\ContractType;

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
     * @see ContractType
     *
     * @return string
     */
    public function getType(): string;

    /**
     * @return string
     */
    public function getLetterNumber(): string;

    /**
     * @return null|string
     */
    public function getDescription(): ? string;

    /**
     * @return \DateTimeInterface
     */
    public function getStartDate(): \DateTimeInterface;

    /**
     * @return \DateTimeInterface|null
     */
    public function getEndDate(): ? \DateTimeInterface;

    /**
     * @return \DateTimeInterface
     */
    public function getSignedDate(): \DateTimeInterface;

    /**
     * Useful for quick search.
     *
     * @return array
     */
    public function getTags(): array;
}
