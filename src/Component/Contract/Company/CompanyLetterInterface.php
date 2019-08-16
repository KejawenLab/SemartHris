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

namespace KejawenLab\Semart\Skeleton\Component\Contract\Company;

use KejawenLab\Semart\Skeleton\Component\Contract\Employee\EmployeeInterface;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
interface CompanyLetterInterface
{
    public const CONTRACT = 'contract';
    public const MUTATION = 'mutasi';
    public const PROMOTION = 'promosi';

    public function getCompany(): ?CompanyInterface;

    public function getLetterType(): ?string;

    public function getLetterNumber(): ?string;

    public function getTitle(): ?string;

    public function getSubject(): ?EmployeeInterface;

    public function getDueDate(): ?\DateTime;

    public function getEndDate(): ?\DateTime;

    public function isUsed(): bool;
}
