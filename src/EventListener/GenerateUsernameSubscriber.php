<?php

declare(strict_types=1);

namespace KejawenLab\Application\SemartHris\EventListener;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Events;
use KejawenLab\Application\SemartHris\Component\User\Model\UserInterface;
use KejawenLab\Application\SemartHris\Component\User\Repository\UserRepositoryInterface;
use KejawenLab\Application\SemartHris\Component\User\Service\UsernameGenerator;
use KejawenLab\Application\SemartHris\Security\Service\PasswordSetter;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class GenerateUsernameSubscriber implements EventSubscriber
{
    /**
     * @var UserRepositoryInterface
     */
    private $userRepository;

    /**
     * @var PasswordSetter
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
     * @param PasswordSetter          $encoder
     * @param UsernameGenerator       $usernameGenerator
     * @param string                  $defaultPassword
     */
    public function __construct(UserRepositoryInterface $repository, PasswordSetter $encoder, UsernameGenerator $usernameGenerator, string $defaultPassword)
    {
        $this->userRepository = $repository;
        $this->passwordEncoder = $encoder;
        $this->defaultPassword = $defaultPassword;
        $this->usernameGenerator = $usernameGenerator;
    }

    /**
     * @param LifecycleEventArgs $event
     */
    public function prePersist(LifecycleEventArgs $event): void
    {
        $entity = $event->getEntity();
        if (!$entity instanceof UserInterface) {
            return;
        }

        $this->generateUsernameAndPassword($entity);
    }

    /**
     * @return array
     */
    public function getSubscribedEvents(): array
    {
        return [Events::prePersist];
    }

    /**
     * @param UserInterface $user
     */
    private function generateUsernameAndPassword(UserInterface $user)
    {
        if ($user->getId()) {
            return;
        }

        $role = [UserInterface::DEFAULT_ROLE];
        if (0 === $this->userRepository->count()) {
            $role = ['ROLE_SUPER_ADMIN'];
        }

        $user->setPlainPassword($this->defaultPassword);
        $user->setUsername($this->usernameGenerator->generate($user));
        $user->setRoles($role);
        $this->passwordEncoder->setPassword($user);
    }
}
