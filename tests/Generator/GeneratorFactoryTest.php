<?php

declare(strict_types=1);

namespace KejawenLab\Semart\Skeleton\Tests\Generator;

use KejawenLab\Semart\Skeleton\Contract\Generator\GeneratorInterface;
use KejawenLab\Semart\Skeleton\Generator\GeneratorFactory;
use PHPUnit\Framework\TestCase;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class GeneratorFactoryTest extends TestCase
{
    public function testGenerate()
    {
        $factory = new GeneratorFactory([
            new class() implements GeneratorInterface {
                use FakeGeneratorTrait;
            },
        ]);

        $this->assertEmpty($factory->generate(new \ReflectionClass(Stub::class)));
    }
}
