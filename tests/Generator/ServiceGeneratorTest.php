<?php

declare(strict_types=1);

namespace KejawenLab\Semart\Skeleton\Tests\Generator;

use KejawenLab\Semart\Skeleton\Contract\Generator\GeneratorInterface;
use KejawenLab\Semart\Skeleton\Generator\ServiceGenerator;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Filesystem\Filesystem;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class ServiceGeneratorTest extends KernelTestCase
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

        $dumpDir = sprintf('%s/src/%s', static::$kernel->getProjectDir(), $reflection->getShortName());
        $generatedServicePath = sprintf('%s/%sService.php', $dumpDir, $reflection->getShortName());

        $generator = new ServiceGenerator(static::$container->get('twig'), $fileSystem, static::$kernel);

        $this->assertInstanceOf(GeneratorInterface::class, $generator);
        $generator->generate($reflection);
        $this->assertFileExists($generatedServicePath);
        $fileSystem->remove($dumpDir);
    }
}
