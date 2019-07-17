<?php

declare(strict_types=1);

namespace KejawenLab\Semart\Skeleton\Tests\Generator;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
trait FakeGeneratorTrait
{
    public function generate(\ReflectionClass $class): void
    {
    }

    public function getPriority(): int
    {
        return 255;
    }
}
