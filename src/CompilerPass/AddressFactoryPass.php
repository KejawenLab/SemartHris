<?php

namespace KejawenLab\Application\SemarHris\CompilerPass;

use KejawenLab\Application\SemarHris\Component\Address\Repository\AddressRepositoryFactory;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@kejawenlab.com>
 */
final class AddressFactoryPass implements CompilerPassInterface
{
    /**
     * @param ContainerBuilder $container
     */
    public function process(ContainerBuilder $container)
    {
        if (!$container->has(AddressRepositoryFactory::class)) {
            return;
        }

        $services = $container->findTaggedServiceIds(AddressRepositoryFactory::ADDRESS_REPOSITORY_SERVICE_TAG);
        $addressRepository = [];
        foreach ($services as $serviceId => $tags) {
            $addressRepository[] = new Reference($serviceId);
        }

        $container->getDefinition(AddressRepositoryFactory::class)->addArgument($addressRepository);
    }
}
