<?php

declare(strict_types=1);

namespace KejawenLab\Semart\Skeleton\Generator;

use Doctrine\Common\Inflector\Inflector;
use PHLAK\Twine\Str;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpKernel\KernelInterface;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class TemplateGenerator extends AbstractGenerator
{
    private $fileSystem;

    private $kernel;

    public function __construct(Filesystem $fileSystem, KernelInterface $kernel)
    {
        $this->fileSystem = $fileSystem;
        $this->kernel = $kernel;
    }

    public function generate(\ReflectionClass $entityClass): void
    {
        $projectDir = $this->kernel->getProjectDir();
        $entity = Str::make($entityClass->getShortName());
        $folderName = Str::make(Inflector::tableize($entity->__toString()))->lowercase()->__toString();

        $search = [
            '{# entity | lower #}',
            '{# entity | upper #}',
            '{# entity | pluralize | lower #}',
            '{# entity | underscore | lower #}',
            '{# entity | pluralize | underscore #}',
            '{# entity | pluralize | camelcase #}',
        ];

        $replace = [
            $entity->lowercase()->__toString(),
            $entity->uppercase()->__toString(),
            Inflector::pluralize($entity->lowercase()->__toString()),
            $folderName,
            Inflector::tableize(Inflector::pluralize($entity->__toString())),
            Inflector::camelize(Inflector::pluralize($entity->__toString())),
        ];

        $indexTemplate = str_replace($search, $replace, (string) file_get_contents(sprintf('%s/templates/generator/index.html.stub', $projectDir)));
        $paginationTemplate = str_replace($search, $replace, (string) file_get_contents(sprintf('%s/templates/generator/pagination.html.stub', $projectDir)));
        $tableTemplate = str_replace($search, $replace, (string) file_get_contents(sprintf('%s/templates/generator/table-content.html.stub', $projectDir)));

        $this->fileSystem->dumpFile(sprintf('%s/templates/%s/index.html.twig', $projectDir, $folderName), $indexTemplate);
        $this->fileSystem->dumpFile(sprintf('%s/templates/%s/pagination.html.twig', $projectDir, $folderName), $paginationTemplate);
        $this->fileSystem->dumpFile(sprintf('%s/templates/%s/table-content.html.twig', $projectDir, $folderName), $tableTemplate);
    }
}
