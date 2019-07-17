<?php

declare(strict_types=1);

namespace KejawenLab\Semart\Skeleton\Generator;

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpKernel\KernelInterface;
use Twig\Environment;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class ControllerGenerator extends AbstractGenerator
{
    private $twig;

    private $fileSystem;

    private $kernel;

    public function __construct(Environment $twig, Filesystem $fileSystem, KernelInterface $kernel)
    {
        $this->twig = $twig;
        $this->fileSystem = $fileSystem;
        $this->kernel = $kernel;
    }

    public function generate(\ReflectionClass $entityClass): void
    {
        $shortName = $entityClass->getShortName();
        $template = $this->twig->render('generator/controller.php.twig', ['entity' => $shortName]);

        $this->fileSystem->dumpFile(sprintf('%s/src/Controller/Admin/%sController.php', $this->kernel->getProjectDir(), $shortName), $template);
    }
}
