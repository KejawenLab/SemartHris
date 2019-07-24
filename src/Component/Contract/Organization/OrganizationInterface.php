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

namespace KejawenLab\Semart\Skeleton\Component\Contract\Organization;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
interface OrganizationInterface
{
    public const LEVEL_DIVISION = 1;
    public const LEVEL_DEPARTMENT = 2;
    public const LEVEL_SECTION = 3;
    public const LEVEL_SUB_SECTION = 4;

    public function getParent(): ?self;

    public function getLevel(): int;

    public function getCode(): ?string;

    public function getName(): ?string;
}
