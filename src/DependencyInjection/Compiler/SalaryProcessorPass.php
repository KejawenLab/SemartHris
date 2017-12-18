<?php

declare(strict_types=1);

namespace KejawenLab\Application\SemartHris\DependencyInjection\Compiler;

use KejawenLab\Application\SemartHris\Component\Salary\Processor\SalaryProcessor;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class SalaryProcessorPass implements CompilerPassInterface
{
    /**
     * @param ContainerBuilder $container
     */
    public function process(ContainerBuilder $container)
    {
        if (!$container->has(SalaryProcessor::class)) {
            return;
        }

        $services = $container->findTaggedServiceIds(SalaryProcessor::SEMARTHRIS_SALARY_PROCESSOR);
        $processors = [];
        foreach ($services as $serviceId => $tags) {
            $processors[] = new Reference($serviceId);
        }

        $container->getDefinition(SalaryProcessor::class)->addArgument($processors);
    }
}
