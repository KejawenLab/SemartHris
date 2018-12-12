<?php

declare(strict_types=1);

namespace KejawenLab\Application\SemartHris\DependencyInjection\Compiler;

use KejawenLab\Application\SemartHris\Component\Overtime\Calculator\OvertimeCalculator;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class OvertimeCalculatorPass implements CompilerPassInterface
{
    /**
     * @param ContainerBuilder $container
     */
    public function process(ContainerBuilder $container)
    {
        if (!$container->has(OvertimeCalculator::class)) {
            return;
        }

        $services = $container->findTaggedServiceIds(OvertimeCalculator::SEMARTHRIS_OVERTIME_CALCULATOR);
        $calculators = [];
        foreach ($services as $serviceId => $tags) {
            $calculators[] = new Reference($serviceId);
        }

        $container->getDefinition(OvertimeCalculator::class)->addArgument($calculators);
    }
}
