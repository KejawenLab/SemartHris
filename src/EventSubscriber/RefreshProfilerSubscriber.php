<?php

declare(strict_types=1);

namespace KejawenLab\Semart\Skeleton\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\KernelInterface;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class RefreshProfilerSubscriber implements EventSubscriberInterface
{
    private $kernel;

    public function __construct(KernelInterface $kernel)
    {
        $this->kernel = $kernel;
    }

    public function refreshProfiler(FilterResponseEvent $event)
    {
        if (!$this->kernel->isDebug()) {
            return;
        }

        $response = $event->getResponse();
        $response->headers->set('Symfony-Debug-Toolbar-Replace', '1');
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::RESPONSE => [['refreshProfiler']],
        ];
    }
}
