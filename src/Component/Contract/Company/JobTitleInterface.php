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

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
interface JobTitleInterface
{
    public const LEVEL_COMMISSIONER = 1;
    public const LEVEL_DIRECTOR = 2;
    public const LEVEL_MANAGER = 3;
    public const LEVEL_SUPERVISOR = 4;
    public const LEVEL_STAFF = 5;

    public const LEVEL_COMMISSIONER_TEXT = 'KOMISARIS';
    public const LEVEL_DIRECTOR_TEXT = 'DIREKTUR';
    public const LEVEL_MANAGER_TEXT = 'MANAGER';
    public const LEVEL_SUPERVISOR_TEXT = 'SUPERVISOR';
    public const LEVEL_STAFF_TEXT = 'STAFF';

    public function getParent(): ?self;

    public function getLevel(): int;

    public function getCode(): ?string;

    public function getName(): ?string;
}
