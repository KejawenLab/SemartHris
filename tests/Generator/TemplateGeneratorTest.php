<?php

declare(strict_types=1);

namespace KejawenLab\Semart\Skeleton\Tests\Generator;

use KejawenLab\Semart\Skeleton\Contract\Generator\GeneratorInterface;
use KejawenLab\Semart\Skeleton\Generator\TemplateGenerator;
use PHLAK\Twine\Str;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Filesystem\Filesystem;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class TemplateGeneratorTest extends KernelTestCase
{
    public function setUp()
    {
        static::bootKernel();
    }

    public function testGenerate()
    {
        $fileSystem = new Filesystem();
        $reflection = new \ReflectionClass(Stub::class);
        $entity = Str::make($reflection->getShortName());

        $dumpDir = sprintf('%s/templates/%s', static::$kernel->getProjectDir(), $entity->lowercase());

        $generator = new TemplateGenerator($fileSystem, static::$kernel);

        $this->assertInstanceOf(GeneratorInterface::class, $generator);
        $this->assertEmpty($generator->generate($reflection));
        $this->assertFileExists(sprintf('%s/index.html.twig', $dumpDir));
        $this->assertFileExists(sprintf('%s/pagination.html.twig', $dumpDir));
        $this->assertFileExists(sprintf('%s/table-content.html.twig', $dumpDir));
        $fileSystem->remove($dumpDir);
    }
}
