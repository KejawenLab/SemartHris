<?php

declare(strict_types=1);

namespace KejawenLab\Semart\Skeleton\Generator;

use KejawenLab\Semart\Collection\Collection;
use KejawenLab\Semart\Skeleton\Contract\Generator\GeneratorInterface;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class GeneratorFactory
{
    private $generators = [];

    public function __construct(array $generators = [])
    {
        Collection::collect($generators)->each(function ($value) {
            $this->addGenerator($value);
        });
    }

    public function generate(\ReflectionClass $entityClass): void
    {
        Collection::collect($this->generators)->each(function ($value) use ($entityClass) {
            /* @var GeneratorInterface $value */
            $value->generate($entityClass);
        });
    }

    private function addGenerator(GeneratorInterface $generator)
    {
        $index = 255 === $generator->getPriority() ?: \count($this->generators);

        $this->generators[$index] = $generator;
    }
}
