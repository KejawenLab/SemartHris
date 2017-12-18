<?php

declare(strict_types=1);

namespace KejawenLab\Application\SemartHris\Component\Holiday\Model;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
interface HolidayInterface
{
    /**
     * @return string
     */
    public function getId(): string;

    /**
     * @return string
     */
    public function getName(): string;

    /**
     * @return \DateTimeInterface
     */
    public function getHolidayDate(): \DateTimeInterface;
}
