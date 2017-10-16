<?php

namespace KejawenLab\Application\SemartHris\Component\Security\Service;

use KejawenLab\Application\SemartHris\Component\Security\Model\UserInterface;
use KejawenLab\Library\PetrukUsername\UsernameFactory;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@kejawenlab.com>
 */
final class UsernameGenerator
{
    /**
     * @var UsernameFactory
     */
    private $usernameFactory;

    /**
     * @param UsernameFactory $usernameFactory
     */
    public function __construct(UsernameFactory $usernameFactory)
    {
        $this->usernameFactory = $usernameFactory;
    }

    /**
     * @param UserInterface $user
     *
     * @return string
     */
    public function generate(UserInterface $user): string
    {
        return $this->usernameFactory->generate($user->getFullName(), $user->getDateOfBirth());
    }
}
