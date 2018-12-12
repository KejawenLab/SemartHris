<?php

declare(strict_types=1);

namespace KejawenLab\Application\SemartHris\DependencyInjection\Compiler;

use KejawenLab\Application\SemartHris\Component\Attendance\Rule\AttendanceRule;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class AttendanceRulePass implements CompilerPassInterface
{
    /**
     * @param ContainerBuilder $container
     */
    public function process(ContainerBuilder $container)
    {
        if (!$container->has(AttendanceRule::class)) {
            return;
        }

        $services = $container->findTaggedServiceIds(AttendanceRule::SEMARTHRIS_ATTENDANCE_RULE);
        $rules = new \SplPriorityQueue();
        foreach ($services as $serviceId => $tags) {
            foreach ($tags as $attributes) {
                $priority = $attributes['priority'] ?? 0;
                $rules->insert(new Reference($serviceId), $priority);
            }
        }

        $container->getDefinition(AttendanceRule::class)->addArgument(iterator_to_array($rules, false));
    }
}
