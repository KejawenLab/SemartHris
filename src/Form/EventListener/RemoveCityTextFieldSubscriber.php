<?php

declare(strict_types=1);

namespace KejawenLab\Application\SemartHris\Form\EventListener;

use KejawenLab\Application\SemartHris\Component\Address\Repository\CityRepositoryInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class RemoveCityTextFieldSubscriber implements EventSubscriberInterface, FieldRemoverInterface
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
    public function remove(FormEvent $event): void
    {
        $form = $event->getForm();
        $data = $event->getData();

        if (isset($data['city']) && $this->cityRepository->find($data['city'])) {
            $form->remove('city_text');
        }

        if (isset($data['cityOfBirth']) && $this->cityRepository->find($data['cityOfBirth'])) {
            $form->remove('city_text');
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
