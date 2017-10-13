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
     * @return \DateTimeInterface
     */
    public function getEndHour(): ? \DateTimeInterface;
}
