<?php

namespace KejawenLab\Application\SemartHris\Form\EventListener;

use KejawenLab\Application\SemartHris\Component\Job\Repository\JobTitleRepositoryInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@kejawenlab.com>
 */
final class RemoveJobTitleTextFieldSubscriber implements EventSubscriberInterface
{
    /**
     * @var JobTitleRepositoryInterface
     */
    private $jobTitleRepository;

    /**
     * @param JobTitleRepositoryInterface $jobTitleRepository
     */
    public function __construct(JobTitleRepositoryInterface $jobTitleRepository)
    {
        $this->jobTitleRepository = $jobTitleRepository;
    }

    /**
     * @param FormEvent $event
     */
    public function removeJobTitleText(FormEvent $event)
    {
        $form = $event->getForm();
        $data = $event->getData();

        if ($this->jobTitleRepository->find($data['jobTitle'])) {
            $form->remove('jobtitle_text');
        }
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [FormEvents::PRE_SUBMIT => 'removeJobTitleText'];
    }
}
