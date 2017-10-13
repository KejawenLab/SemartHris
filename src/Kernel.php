<?php

namespace KejawenLab\Application\SemartHris;

use KejawenLab\Application\SemartHris\CompilerPass\ServiceCompilerPassFactory;
use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;
use Symfony\Component\Routing\RouteCollectionBuilder;

class Kernel extends BaseKernel implements CompilerPassInterface
{
    use MicroKernelTrait;

    const CONFIG_EXTS = '.{php,xml,yaml,yml}';

    /**
     * @return string
     */
    public function getCacheDir(): string
    {
        return dirname(__DIR__).'/var/cache/'.$this->environment;
    }

    /**
     * @return string
     */
    public function getLogDir(): string
    {
        return dirname(__DIR__).'/var/log';
    }

    public function registerBundles()
    {
        $contents = require dirname(__DIR__).'/config/bundles.php';
        foreach ($contents as $class => $envs) {
            if (isset($envs['all']) || isset($envs[$this->environment])) {
                yield new $class();
            }
        }
    }

    /**
     * @param ContainerBuilder $container
     * @param LoaderInterface  $loader
     */
    protected function configureContainer(ContainerBuilder $container, LoaderInterface $loader)
    {
        $confDir = dirname(__DIR__).'/config';
        $loader->load($confDir.'/packages/*'.self::CONFIG_EXTS, 'glob');
        if (is_dir($confDir.'/packages/'.$this->environment)) {
            $loader->load($confDir.'/packages/'.$this->environment.'/**/*'.self::CONFIG_EXTS, 'glob');
        }
        $loader->load($confDir.'/admin/*'.self::CONFIG_EXTS, 'glob');
        $loader->load($confDir.'/semar/*'.self::CONFIG_EXTS, 'glob');
        $loader->load($confDir.'/services'.self::CONFIG_EXTS, 'glob');
        $loader->load($confDir.'/services_'.$this->environment.self::CONFIG_EXTS, 'glob');
    }

    /**
     * @param RouteCollectionBuilder $routes
     */
    protected function configureRoutes(RouteCollectionBuilder $routes)
    {
        $confDir = dirname(__DIR__).'/config';
        if (is_dir($confDir.'/routes/')) {
            $routes->import($confDir.'/routes/*'.self::CONFIG_EXTS, '/', 'glob');
        }
        if (is_dir($confDir.'/routes/'.$this->environment)) {
            $routes->import($confDir.'/routes/'.$this->environment.'/**/*'.self::CONFIG_EXTS, '/', 'glob');
        }
        $routes->import($confDir.'/routes'.self::CONFIG_EXTS, '/', 'glob');
    }

    /**
     * @param ContainerBuilder $container
     */
    public function process(ContainerBuilder $container)
    {
        if ($container->hasDefinition(ServiceCompilerPassFactory::class)) {
            $services = $container->findTaggedServiceIds(ServiceCompilerPassFactory::SERVICE_COMPILER_PASS_TAG);
            $serviceCompilers = [];
            foreach ($services as $serviceId => $tags) {
                $serviceCompilers[] = new Reference($serviceId);
            }

            $container->getDefinition(ServiceCompilerPassFactory::class)->addArgument($serviceCompilers);

            $container->getDefinition(ServiceCompilerPassFactory::class)->addMethodCall('compile', [$container]);
        }
    }
}
