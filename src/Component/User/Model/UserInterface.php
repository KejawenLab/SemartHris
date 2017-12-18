<?php

declare(strict_types=1);

namespace KejawenLab\Application\SemartHris\Component\User\Model;

use KejawenLab\Library\PetrukUsername\Repository\UsernameInterface;
use Symfony\Component\Security\Core\User\UserInterface as SymfonyUser;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
interface UserInterface extends UsernameInterface, SymfonyUser
{
    const DEFAULT_ROLE = 'ROLE_EMPLOYEE';

    /**
     * @param string $password
     */
    public function setPassword(string $password): void;

    /**
     * @return string
     */
    public function getPlainPassword(): ? string;

    /**
     * @param string|null $plainPassword
     */
    public function setPlainPassword(?string $plainPassword): void;

    /**
     * @param string $role
     */
    public function addRole(string $role): void;

    /**
     * @param array $roles
     */
    public function setRoles(array $roles): void;
}
