<?php

namespace KejawenLab\Application\SemartHris\Component\Attendance\Model;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@kejawenlab.com>
 */
interface ShiftmentInterface
{
    /**
     * @return string
     */
    public function getId(): string;

    /**
     * @return string
     */
    public function getCode(): string;

    /**
     * @return string
     */
    public function getName(): string;

    /**
     * @return \DateTimeInterface
     */
    public function getStartHour(): ? \DateTimeInterface;

    /**
     * @param \DateTimeInterface|null $startHour
     */
    public function setStartHour(\DateTimeInterface $startHour = null): void;

    /**
     * @return \DateTimeInterface
     */
    public function getEndHour(): ? \DateTimeInterface;

    /**
     * @param \DateTimeInterface|null $endHour
     */
    public function setEndHour(\DateTimeInterface $endHour = null): void;
}
