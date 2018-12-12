<?php

declare(strict_types=1);

namespace KejawenLab\Application\SemartHris\Security\Service;

use KejawenLab\Application\SemartHris\Component\User\Model\UserInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class PasswordSetter
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
    public function setPassword(UserInterface $user): void
    {
        if ($plainPassword = $user->getPlainPassword()) {
            $user->setPassword($this->passwordEncoder->encodePassword($user, $plainPassword));
            $user->setPlainPassword(null);
        }
    }
}
