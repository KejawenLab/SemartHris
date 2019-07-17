<?php

declare(strict_types=1);

namespace KejawenLab\Semart\Skeleton\Contract\Generator;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
interface GeneratorInterface
{
    public const DEFAULT_PRIORITY = 0;

    public function generate(\ReflectionClass $entityClass): void;

    public function getPriority(): int;
}
