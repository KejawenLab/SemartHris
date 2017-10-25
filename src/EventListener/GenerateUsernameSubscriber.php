<?php

namespace KejawenLab\Application\SemartHris\EventListener;

use ApiPlatform\Core\EventListener\EventPriorities;
use EasyCorp\Bundle\EasyAdminBundle\Event\EasyAdminEvents;
use KejawenLab\Application\SemartHris\Component\User\Model\UserInterface;
use KejawenLab\Application\SemartHris\Component\User\Repository\UserRepositoryInterface;
use KejawenLab\Application\SemartHris\Component\User\Service\UsernameGenerator;
use KejawenLab\Application\SemartHris\Security\Service\EncodePasswordService;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\EventDispatcher\GenericEvent;
use Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@kejawenlab.com>
 */
final class GenerateUsernameSubscriber implements EventSubscriberInterface
{
    /**
     * @var UserRepositoryInterface
     */
    private $userRepository;

    /**
     * @var EncodePasswordService
     */
    private $passwordEncoder;

    /**
     * @var UsernameGenerator
     */
    private $usernameGenerator;

    /**
     * @var string
     */
    private $defaultPassword;

    /**
     * @param UserRepositoryInterface $repository
     * @param EncodePasswordService   $encoder
     * @param UsernameGenerator       $usernameGenerator
     * @param string                  $defaultPassword
     */
    public function __construct(UserRepositoryInterface $repository, EncodePasswordService $encoder, UsernameGenerator $usernameGenerator, string $defaultPassword)
    {
        $this->userRepository = $repository;
        $this->passwordEncoder = $encoder;
        $this->defaultPassword = $defaultPassword;
        $this->usernameGenerator = $usernameGenerator;
    }

    /**
     * @param GenericEvent $event
     */
    public function generateFromGenericEvent(GenericEvent $event): void
    {
        $entity = $event->getSubject();
        if (!$entity instanceof UserInterface) {
            return;
        }

        $this->generateUsernameAndPassword($entity);
    }

    /**
     * @param GetResponseForControllerResultEvent $event
     */
    public function generateFromControllerEvent(GetResponseForControllerResultEvent $event)
    {
        $entity = $event->getControllerResult();
        if (!$entity instanceof UserInterface) {
            return;
        }

        $this->generateUsernameAndPassword($entity);
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents(): array
    {
        return [
            EasyAdminEvents::PRE_PERSIST => ['generateFromGenericEvent', 0],
            KernelEvents::VIEW => ['generateFromControllerEvent', EventPriorities::PRE_WRITE],
        ];
    }

    /**
     * @param UserInterface $user
     */
    private function generateUsernameAndPassword(UserInterface $user)
    {
        if ($user->getId()) {
            return;
        }

        $role = ['ROLE_EMPLOYEE'];
        if (0 === $this->userRepository->count()) {
            $role = ['ROLE_SUPER_ADMIN'];
        }

        $user->setUsername($this->usernameGenerator->generate($user));
        $user->setRoles($role);
        $this->passwordEncoder->setPassword($user);
    }
}
