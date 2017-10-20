<?php

namespace KejawenLab\Application\SemartHris\DependencyInjection\Compiler;

use KejawenLab\Application\SemartHris\Component\Contract\Repository\ContractableRepositoryFactory;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@kejawenlab.com>
 */
final class ContractableRepositoryFactoryPass implements CompilerPassInterface
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
        $addressRepository = [];
        foreach ($services as $serviceId => $tags) {
            $addressRepository[] = new Reference($serviceId);
        }

        $container->getDefinition(ContractableRepositoryFactory::class)->addArgument($addressRepository);
    }
}
