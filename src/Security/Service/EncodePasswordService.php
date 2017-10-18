<?php

namespace KejawenLab\Application\SemartHris\Security\Service;

use KejawenLab\Application\SemartHris\Component\Security\Model\UserInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@kejawenlab.com>
 */
class EncodePasswordService
{
    /**
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;

    /**
     * @param UserPasswordEncoderInterface $encoder
     */
    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->passwordEncoder = $encoder;
    }

    /**
     * @param UserInterface $user
     */
    public function encodeAndApply(UserInterface $user): void
    {
        if ($plainPassword = $user->getPlainPassword()) {
            $user->setPassword($this->passwordEncoder->encodePassword($user, $plainPassword));
        }
    }
}
