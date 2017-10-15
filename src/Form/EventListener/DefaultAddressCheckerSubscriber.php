<?php

namespace KejawenLab\Application\SemartHris\Form\EventListener;

use KejawenLab\Application\SemartHris\Component\Address\Service\DefaultAddressChecker;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@kejawenlab.com>
 */
final class DefaultAddressCheckerSubscriber implements EventSubscriberInterface
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
    public function unsetDefaultExcept(FormEvent $event)
    {
        $this->defaultAddressChecker->unsetDefaultExcept($event->getData());
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [FormEvents::POST_SUBMIT => 'unsetDefaultExcept'];
    }
}
