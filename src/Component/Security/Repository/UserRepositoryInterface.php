<?php

namespace KejawenLab\Application\SemartHris\Component\Security\Repository;

use KejawenLab\Application\SemartHris\Component\Security\Model\UserInterface;
use KejawenLab\Library\PetrukUsername\Repository\UsernameRepositoryInterface;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@kejawenlab.com>
 */
interface UserRepositoryInterface extends UsernameRepositoryInterface
{
    /**
     * @param string $username
     *
     * @return UserInterface|null
     */
    public function findByUsername(string $username): ? UserInterface;
}
