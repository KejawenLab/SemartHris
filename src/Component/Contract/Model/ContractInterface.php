<?php

declare(strict_types=1);

namespace KejawenLab\Application\SemartHris\Component\Contract\Model;

use KejawenLab\Application\SemartHris\Component\Contract\ContractType;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
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
     * @return string
     */
    public function getSubject(): string;

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

    /**
     * @return bool
     */
    public function isUsed(): bool;

    /**
     * @param bool $used
     */
    public function setUsed(bool $used): void;
}
