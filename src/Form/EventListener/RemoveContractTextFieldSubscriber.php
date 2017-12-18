<?php

declare(strict_types=1);

namespace KejawenLab\Application\SemartHris\Form\EventListener;

use KejawenLab\Application\SemartHris\Component\Contract\Repository\ContractRepositoryInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class RemoveContractTextFieldSubscriber implements EventSubscriberInterface, FieldRemoverInterface
{
    /**
     * @var ContractRepositoryInterface
     */
    private $contractRepository;

    /**
     * @param ContractRepositoryInterface $contractRepository
     */
    public function __construct(ContractRepositoryInterface $contractRepository)
    {
        $this->contractRepository = $contractRepository;
    }

    /**
     * @param FormEvent $event
     */
    public function remove(FormEvent $event): void
    {
        $form = $event->getForm();
        $data = $event->getData();

        if (isset($data['contract']) && $this->contractRepository->find($data['contract'])) {
            $form->remove('contract_text');
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
