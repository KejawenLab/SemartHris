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
interface AttendanceInterface
{
    public function getId(): string;

    public function getEmployee(): ?EmployeeInterface;

    public function setEmployee(EmployeeInterface $employee): void;

    public function getShiftment(): ?ShiftmentInterface;

    public function setShiftment(ShiftmentInterface $shiftment): void;

    public function getAttendanceDate(): ?\DateTimeInterface;

    public function setAttendanceDate(\DateTimeInterface $date): void;

    public function getDescription(): ?string;

    public function setDescription(string $description): void;

    public function getCheckIn(): ?\DateTimeInterface;

    public function setCheckIn(\DateTimeInterface $time): void;

    public function getCheckOut(): ?\DateTimeInterface;

    public function setCheckOut(?\DateTimeInterface $time): void;

    public function getEarlyIn(): int;

    public function setEarlyIn(int $minutes): void;

    public function getEarlyOut(): int;

    public function setEarlyOut(int $minutes): void;

    public function getLateIn(): int;

    public function setLateIn(int $minutes): void;

    public function getLateOut(): int;

    public function setLateOut(int $minutes): void;

    public function isAbsent(): bool;

    public function setAbsent(bool $isAbsent): void;

    public function getReason(): ?ReasonInterface;

    public function setReason(?ReasonInterface $reason): void;
}
