<?php

declare(strict_types=1);

namespace KejawenLab\Application\SemartHris\Form\EventListener;

use KejawenLab\Application\SemartHris\Component\Address\Service\DefaultAddressChecker;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class DefaultAddressCheckerSubscriber implements EventSubscriberInterface
{
    /**
     * @var DefaultAddressChecker
     */
    private $defaultAddressChecker;

    /**
     * @param DefaultAddressChecker $defaultAddressChecker
     */
    public function __construct(DefaultAddressChecker $defaultAddressChecker)
    {
        $this->defaultAddressChecker = $defaultAddressChecker;
    }

    /**
     * @param FormEvent $event
     */
    public function unsetDefaultExcept(FormEvent $event): void
    {
        $this->defaultAddressChecker->unsetDefaultExcept($event->getData());
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents(): array
    {
        return [FormEvents::POST_SUBMIT => 'unsetDefaultExcept'];
    }
}
