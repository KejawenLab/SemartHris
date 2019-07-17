<?php

declare(strict_types=1);

namespace KejawenLab\Semart\Skeleton\Tests\Generator;

use KejawenLab\Semart\Skeleton\Contract\Generator\GeneratorInterface;
use KejawenLab\Semart\Skeleton\Generator\RepositoryGenerator;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Filesystem\Filesystem;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class RepositoryGeneratorTest extends KernelTestCase
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

        $dumpDir = sprintf('%s/src/Repository', static::$kernel->getProjectDir());
        $generatedRepositoryPath = sprintf('%s/%sRepository.php', $dumpDir, $reflection->getShortName());

        $generator = new RepositoryGenerator(static::$container->get('twig'), $fileSystem, static::$kernel);

        $this->assertInstanceOf(GeneratorInterface::class, $generator);
        $generator->generate($reflection);
        $this->assertFileExists($generatedRepositoryPath);
        $fileSystem->remove($generatedRepositoryPath);
    }
}
