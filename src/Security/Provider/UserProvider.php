<?php

declare(strict_types=1);

namespace KejawenLab\Application\SemartHris\Security\Provider;

use Doctrine\Common\Util\ClassUtils;
use KejawenLab\Application\SemartHris\Component\User\Model\UserInterface as BaseUser;
use KejawenLab\Application\SemartHris\Component\User\Repository\UserRepositoryInterface;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class UserProvider implements UserProviderInterface
{
    /**
     * @var UserRepositoryInterface
     */
    private $userRepository;

    /**
     * @param UserRepositoryInterface $userRepository
     */
    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * @param string $username The username
     *
     * @return UserInterface
     *
     * @throws UsernameNotFoundException if the user is not found
     */
    public function loadUserByUsername($username)
    {
        if (!$user = $this->userRepository->findByUsername($username)) {
            throw new UsernameNotFoundException(sprintf('Username "%s" does not exist.', $username));
        }

        return $user;
    }

    /**
     * @param UserInterface $user
     *
     * @return UserInterface
     *
     * @throws UnsupportedUserException if the user is not supported
     */
    public function refreshUser(UserInterface $user)
    {
        $realClass = ClassUtils::getRealClass(get_class($user));
        if (!$this->supportsClass($realClass)) {
            throw new UnsupportedUserException(sprintf('Object class "%s" is not supported', $realClass));
        }

        return $this->userRepository->findByUsername($user->getUsername());
    }

    /**
     * @param string $class
     *
     * @return bool
     */
    public function supportsClass($class)
    {
        $reflection = new \ReflectionClass($class);

        return $reflection->implementsInterface(BaseUser::class);
    }
}
