<?php

namespace KejawenLab\Application\SemartHris\Form\EventListener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@kejawenlab.com>
 */
final class RemoveReasonTextFieldSubscriber implements EventSubscriberInterface
{
    /**
     * @param FormEvent $event
     */
    public function removeReasonText(FormEvent $event): void
    {
        $event->getForm()->remove('reason_text');
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents(): array
    {
        return [FormEvents::PRE_SUBMIT => 'removeReasonText'];
    }
}
