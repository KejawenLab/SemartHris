<?php

declare(strict_types=1);

namespace KejawenLab\Semart\Skeleton\Generator;

use KejawenLab\Semart\Skeleton\Contract\Generator\GeneratorInterface;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
abstract class AbstractGenerator implements GeneratorInterface
{
    public function getPriority(): int
    {
        return self::DEFAULT_PRIORITY;
    }
}
