<?php

declare(strict_types=1);

namespace KejawenLab\Application\SemartHris\EventListener;

use ApiPlatform\Core\EventListener\EventPriorities;
use KejawenLab\Application\SemartHris\Component\Address\Model\AddressInterface;
use KejawenLab\Application\SemartHris\Component\Address\Service\DefaultAddressChecker;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class DefaultAddressCheckerSubscriber implements EventSubscriberInterface
{
    /**
     * @var DefaultAddressChecker
     */
    private $addressChecker;

    /**
     * @param DefaultAddressChecker $addressChecker
     */
    public function __construct(DefaultAddressChecker $addressChecker)
    {
        $this->addressChecker = $addressChecker;
    }

    /**
     * @param GetResponseForControllerResultEvent $event
     */
    public function checkDefaultAddress(GetResponseForControllerResultEvent $event): void
    {
        $entity = $event->getControllerResult();
        if ($entity instanceof AddressInterface && $entity->isDefaultAddress()) {
            $request = $event->getRequest();
            if (in_array($request->getMethod(), ['POST', 'PUT'])) {
                $this->addressChecker->unsetDefaultExcept($entity);
            }

            if ('DELETE' === $request->getMethod()) {
                $this->addressChecker->setRandomDefault($entity);
            }
        }
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::VIEW => ['checkDefaultAddress', EventPriorities::POST_WRITE],
        ];
    }
}
