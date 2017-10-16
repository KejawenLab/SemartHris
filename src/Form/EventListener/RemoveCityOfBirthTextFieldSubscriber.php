<?php

namespace KejawenLab\Application\SemartHris\Form\EventListener;

use KejawenLab\Application\SemartHris\Component\Address\Repository\CityRepositoryInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@kejawenlab.com>
 */
final class RemoveCityOfBirthTextFieldSubscriber implements EventSubscriberInterface
{
    /**
     * @var CityRepositoryInterface
     */
    private $cityRepository;

    /**
     * @param CityRepositoryInterface $cityRepository
     */
    public function __construct(CityRepositoryInterface $cityRepository)
    {
        $this->cityRepository = $cityRepository;
    }

    /**
     * @param FormEvent $event
     */
    public function removeCityText(FormEvent $event): void
    {
        $form = $event->getForm();
        $data = $event->getData();

        if ($this->cityRepository->find($data['cityOfBirth'])) {
            $form->remove('city_text');
        }
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents(): array
    {
        return [FormEvents::PRE_SUBMIT => 'removeCityText'];
    }
}
