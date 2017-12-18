<?php

declare(strict_types=1);

namespace KejawenLab\Application\SemartHris\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Yaml\Yaml;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class CompilerPassFactory
{
    const COMPILER_PATH = 'config/compiler.yaml';

    /**
     * @var ContainerBuilder
     */
    private $container;

    /**
     * @param ContainerBuilder $container
     */
    public function __construct(ContainerBuilder $container)
    {
        $this->container = $container;
    }

    public function compile(): void
    {
        $path = sprintf('%s/%s', $this->container->getParameter('kernel.project_dir'), self::COMPILER_PATH);
        $compilers = Yaml::parse(file_get_contents($path));
        foreach ($compilers as $compiler) {
            /** @var CompilerPassInterface $compiler */
            $compiler = new $compiler();
            $compiler->process($this->container);
        }
    }
}
