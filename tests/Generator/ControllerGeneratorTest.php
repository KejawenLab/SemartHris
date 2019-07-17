<?php

declare(strict_types=1);

namespace KejawenLab\Semart\Skeleton\Tests\Generator;

use KejawenLab\Semart\Skeleton\Contract\Generator\GeneratorInterface;
use KejawenLab\Semart\Skeleton\Generator\ControllerGenerator;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Filesystem\Filesystem;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class ControllerGeneratorTest extends KernelTestCase
{
    public function setUp()
    {
        static::bootKernel();
    }

    public function testGenerate()
    {
        static::$container->get('twig');
        $fileSystem = new Filesystem();
        $reflection = new \ReflectionClass(Stub::class);

        $dumpDir = sprintf('%s/src/Controller/Admin', static::$kernel->getProjectDir());
        $generatedControllerPath = sprintf('%s/%sController.php', $dumpDir, $reflection->getShortName());

        $generator = new ControllerGenerator(static::$container->get('twig'), $fileSystem, static::$kernel);

        $this->assertInstanceOf(GeneratorInterface::class, $generator);
        $generator->generate($reflection);
        $this->assertFileExists($generatedControllerPath);
        $fileSystem->remove($generatedControllerPath);
    }
}
