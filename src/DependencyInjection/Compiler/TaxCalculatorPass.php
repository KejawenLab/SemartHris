<?php

declare(strict_types=1);

namespace KejawenLab\Application\SemartHris\DependencyInjection\Compiler;

use KejawenLab\Application\SemartHris\Component\Tax\Processor\TaxProcessor;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class TaxCalculatorPass implements CompilerPassInterface
{
    /**
     * @param ContainerBuilder $container
     */
    public function process(ContainerBuilder $container)
    {
        if (!$container->has(TaxProcessor::class)) {
            return;
        }

        $services = $container->findTaggedServiceIds(TaxProcessor::SEMARTHRIS_TAX_CALCULATOR);
        $calculators = [];
        foreach ($services as $serviceId => $tags) {
            $calculators[] = new Reference($serviceId);
        }

        $container->getDefinition(TaxProcessor::class)->addArgument($calculators);
    }
}
