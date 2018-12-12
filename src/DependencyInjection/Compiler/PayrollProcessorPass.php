<?php

declare(strict_types=1);

namespace KejawenLab\Application\SemartHris\DependencyInjection\Compiler;

use KejawenLab\Application\SemartHris\Component\Salary\Processor\PayrollProcessor;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class PayrollProcessorPass implements CompilerPassInterface
{
    /**
     * @param ContainerBuilder $container
     */
    public function process(ContainerBuilder $container)
    {
        if (!$container->has(PayrollProcessor::class)) {
            return;
        }

        $services = $container->findTaggedServiceIds(PayrollProcessor::SEMARTHRIS_PAYROLL_PROCESSOR);
        $processors = [];
        foreach ($services as $serviceId => $tags) {
            $processors[] = new Reference($serviceId);
        }

        $container->getDefinition(PayrollProcessor::class)->addArgument($processors);
    }
}
