<?php

namespace KejawenLab\Application\SemarHris\Component\Attendance\Model;

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
     * @return string
     */
    public function getStartHour(): string;

    /**
     * @return string
     */
    public function getStartMinute(): string;

    /**
     * @return string
     */
    public function getEndHour(): string;

    /**
     * @return string
     */
    public function getEndMinute(): string;
}
