<?php

declare(strict_types=1);

namespace KejawenLab\Semart\Skeleton\Security\Service;

use KejawenLab\Semart\Skeleton\Entity\User;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class PasswordEncoderService
{
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function encode(User $user)
    {
        if ($plainPassword = $user->getPlainPassword()) {
            $user->setPassword($this->passwordEncoder->encodePassword($user, $plainPassword));
        }
    }
}
