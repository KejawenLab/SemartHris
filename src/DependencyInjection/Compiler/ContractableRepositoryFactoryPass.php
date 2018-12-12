<?php

declare(strict_types=1);

namespace KejawenLab\Application\SemartHris\DependencyInjection\Compiler;

use KejawenLab\Application\SemartHris\Component\Contract\Repository\ContractableRepositoryFactory;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class ContractableRepositoryFactoryPass implements CompilerPassInterface
{
    /**
     * @param ContainerBuilder $container
     */
    public function process(ContainerBuilder $container)
    {
        if (!$container->has(ContractableRepositoryFactory::class)) {
            return;
        }

        $services = $container->findTaggedServiceIds(ContractableRepositoryFactory::SEMARTHRIS_CONTRACTABLE_REPOSITORY);
        $repositories = [];
        foreach ($services as $serviceId => $tags) {
            $repositories[] = new Reference($serviceId);
        }

        $container->getDefinition(ContractableRepositoryFactory::class)->addArgument($repositories);
    }
}
