<?php

namespace KejawenLab\Application\SemartHris\Form\EventListener;

use KejawenLab\Application\SemartHris\Component\Address\Repository\CityRepositoryInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@kejawenlab.com>
 */
final class RemoveTagsTextFieldSubscriber implements EventSubscriberInterface
{
    /**
     * @param FormEvent $event
     */
    public function removeTagsText(FormEvent $event): void
    {
        $form = $event->getForm();
        $data = $event->getData();

        if ($data['tags']) {
            $form->remove('tags_text');
        }
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents(): array
    {
        return [FormEvents::PRE_SUBMIT => 'removeTagsText'];
    }
}
