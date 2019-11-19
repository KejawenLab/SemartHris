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

use KejawenLab\Semart\Skeleton\Component\Contract\Employee\EmployeeInterface;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
interface WorkshiftInterface
{
    public function getId(): string;

    public function getEmployee(): ?EmployeeInterface;

    public function setEmployee(?EmployeeInterface $employee): void;

    public function getShiftment(): ?ShiftmentInterface;

    public function setShiftment(?ShiftmentInterface $shiftment): void;

    public function getDescription(): ?string;

    public function setDescription(?string $description): void;

    public function getStartDate(): ?\DateTimeInterface;

    public function setStartDate(?\DateTimeInterface $startDate): void;

    public function getEndDate(): ?\DateTimeInterface;

    public function setEndDate(?\DateTimeInterface $endDate): void;
}
