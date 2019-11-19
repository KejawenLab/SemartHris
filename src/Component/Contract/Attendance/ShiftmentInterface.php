<?php
/**
 * This file is part of the Semart HRIS Application.
 *
 * (c) Muhamad Surya Iksanudin <surya.kejawen@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace KejawenLab\Semart\Skeleton\Component\Contract\Attendance;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
interface ShiftmentInterface
{
    public function getId(): string;

    public function getCode(): string;

    public function getName(): string;

    public function getStartHour(): \DateTimeInterface;

    public function setStartHour(\DateTimeInterface $startHour): void;

    public function getEndHour(): \DateTimeInterface;

    public function setEndHour(\DateTimeInterface $endHour): void;
}
