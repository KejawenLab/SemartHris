<?php

declare(strict_types=1);

namespace KejawenLab\Application\SemartHris\Component\Leave\Model;

use KejawenLab\Application\SemartHris\Component\Employee\Model\EmployeeInterface;
use KejawenLab\Application\SemartHris\Component\Reason\Model\ReasonInterface;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
interface LeaveInterface
{
    /**
     * @return string
     */
    public function getId(): string;

    /**
     * @return null|EmployeeInterface
     */
    public function getEmployee(): ? EmployeeInterface;

    /**
     * @param EmployeeInterface|null $employee
     */
    public function setEmployee(?EmployeeInterface $employee): void;

    /**
     * @return \DateTimeInterface
     */
    public function getLeaveDate(): ? \DateTimeInterface;

    /**
     * @param \DateTimeInterface|null $date
     */
    public function setLeaveDate(?\DateTimeInterface $date): void;

    /**
     * @return null|ReasonInterface
     */
    public function getReason(): ? ReasonInterface;

    /**
     * @param ReasonInterface $reason
     */
    public function setReason(?ReasonInterface $reason): void;

    /**
     * @return int
     */
    public function getAmount(): int;

    /**
     * @param int|null $amount
     */
    public function setAmount(?int $amount): void;

    /**
     * @return null|string
     */
    public function getDescription(): ? string;

    /**
     * @param null|string $description
     */
    public function setDescription(?string $description): void;
}
