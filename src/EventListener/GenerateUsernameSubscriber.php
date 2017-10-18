<?php

namespace KejawenLab\Application\SemartHris\EventListener;

use ApiPlatform\Core\EventListener\EventPriorities;
use EasyCorp\Bundle\EasyAdminBundle\Event\EasyAdminEvents;
use KejawenLab\Application\SemartHris\Component\Security\Model\UserInterface;
use KejawenLab\Application\SemartHris\Component\Security\Service\UsernameGenerator;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\EventDispatcher\GenericEvent;
use Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@kejawenlab.com>
 */
final class GenerateUsernameSubscriber implements EventSubscriberInterface
{
    /**
     * @var UserPasswordEncoderInterface
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
     * @param UserPasswordEncoderInterface $encoder
     * @param UsernameGenerator            $usernameGenerator
     * @param string                       $defaultPassword
     */
    public function __construct(UserPasswordEncoderInterface $encoder, UsernameGenerator $usernameGenerator, string $defaultPassword)
    {
        $this->passwordEncoder = $encoder;
        $this->defaultPassword = $defaultPassword;
        $this->usernameGenerator = $usernameGenerator;
    }

    /**
     * @param GenericEvent $event
     */
    public function generateFromGenericEvent(GenericEvent $event): void
    {
        $user = $event->getSubject();
        if (!$user instanceof UserInterface) {
            return;
        }

        $this->generateUsernameAndPassword($user);
    }

    /**
     * @param GetResponseForControllerResultEvent $event
     */
    public function generateFromControllerEvent(GetResponseForControllerResultEvent $event)
    {
        $user = $event->getControllerResult();
        if (!$user instanceof UserInterface) {
            return;
        }

        $this->generateUsernameAndPassword($user);
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

        $user->setPassword($this->passwordEncoder->encodePassword($user, $this->defaultPassword));
        $user->setUsername($this->usernameGenerator->generate($user));
        $user->setRoles(['ROLE_EMPLOYEE']);
    }
}
