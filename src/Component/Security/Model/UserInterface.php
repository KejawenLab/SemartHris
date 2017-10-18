<?php

namespace KejawenLab\Application\SemartHris\Component\Security\Model;

use KejawenLab\Library\PetrukUsername\Repository\UsernameInterface;
use Symfony\Component\Security\Core\User\UserInterface as SymfonyUser;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@kejawenlab.com>
 */
interface UserInterface extends UsernameInterface, SymfonyUser
{
    /**
     * @param string $password
     */
    public function setPassword(string $password): void;

    /**
     * @return string
     */
    public function getPlainPassword(): ? string;

    /**
     * @param string $role
     */
    public function addRole(string $role): void;

    /**
     * @param array $roles
     */
    public function setRoles(array $roles): void;
}
