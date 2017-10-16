<?php

namespace KejawenLab\Application\SemartHris\EventListener;

use ApiPlatform\Core\EventListener\EventPriorities;
use KejawenLab\Application\SemartHris\Component\Address\Model\AddressInterface;
use KejawenLab\Application\SemartHris\Component\Address\Service\DefaultAddressChecker;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@kejawenlab.com>
 */
final class DefaultAddressCheckerSubscriber implements EventSubscriberInterface
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
        $data = $event->getControllerResult();
        if ($data instanceof AddressInterface && $data->isDefaultAddress()) {
            $request = $event->getRequest();
            if (in_array($request->getMethod(), ['POST', 'PUT'])) {
                $this->addressChecker->unsetDefaultExcept($data);
            }

            if ('DELETE' === $request->getMethod()) {
                $this->addressChecker->setRandomDefault($data);
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
