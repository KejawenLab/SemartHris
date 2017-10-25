<?php

namespace KejawenLab\Application\SemartHris\Form\EventListener;

use KejawenLab\Application\SemartHris\Component\User\Model\UserInterface;
use KejawenLab\Application\SemartHris\Security\Service\EncodePasswordService;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@kejawenlab.com>
 */
final class EncodePasswordSubscriber implements EventSubscriberInterface
{
    /**
     * @var EncodePasswordService
     */
    private $passwordEncoderService;

    /**
     * @param EncodePasswordService $service
     */
    public function __construct(EncodePasswordService $service)
    {
        $this->passwordEncoderService = $service;
    }

    /**
     * @param FormEvent $event
     */
    public function setPassword(FormEvent $event): void
    {
        /** @var UserInterface $data */
        $data = $event->getData();
        $this->passwordEncoderService->setPassword($data);
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents(): array
    {
        return [FormEvents::POST_SUBMIT => 'setPassword'];
    }
}
