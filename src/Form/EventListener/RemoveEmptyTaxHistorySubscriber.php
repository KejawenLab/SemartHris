<?php

declare(strict_types=1);

namespace KejawenLab\Application\SemartHris\Form\EventListener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class RemoveEmptyTaxHistorySubscriber implements EventSubscriberInterface, FieldRemoverInterface
{
    /**
     * @param FormEvent $event
     */
    public function remove(FormEvent $event): void
    {
        $form = $event->getForm();
        $data = $event->getData();

        if (!(isset($data['newTaxGroup']) && $data['newTaxGroup'])) {
            $form->remove('newTaxGroup');
        }

        if (!(isset($data['newRiskRatio']) && $data['newRiskRatio'])) {
            $form->remove('newRiskRatio');
        }
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents(): array
    {
        return [FormEvents::PRE_SUBMIT => 'remove'];
    }
}
