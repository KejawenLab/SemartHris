<?php

declare(strict_types=1);

namespace KejawenLab\Semart\Skeleton\Security\Service;

use KejawenLab\Semart\Skeleton\Contract\Service\ServiceInterface;
use KejawenLab\Semart\Skeleton\Entity\User;
use KejawenLab\Semart\Skeleton\Repository\UserRepository;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class UserService implements UserProviderInterface, ServiceInterface
{
    private $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $userRepository->setCacheable(true);
        $this->userRepository = $userRepository;
    }

    public function loadUserByUsername($username): UserInterface
    {
        $user = $this->userRepository->findOneBy(['username' => $username]);

        if (!$user instanceof UserInterface) {
            throw new UsernameNotFoundException();
        }

        return $user;
    }

    public function refreshUser(UserInterface $user): UserInterface
    {
        return $this->loadUserByUsername($user->getUsername());
    }

    public function supportsClass($class): bool
    {
        return User::class === $class;
    }

    /**
     * @param string $id
     *
     * @return User|null
     */
    public function get(string $id): ?object
    {
        return $this->userRepository->find($id);
    }
}
