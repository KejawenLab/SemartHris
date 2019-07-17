<?php

declare(strict_types=1);

namespace KejawenLab\Semart\Skeleton\Controller\Front;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class SecurityController extends AbstractController
{
    /**
     * @Route("/login", name="login")
     */
    public function login(AuthenticationUtils $authenticationUtils)
    {
        return $this->render('security/login.html.twig', [
            'username' => $authenticationUtils->getLastUsername(),
            'error' => $authenticationUtils->getLastAuthenticationError(),
        ]);
    }

    /**
     * @Route("/admin/login_check", name="login_check")
     */
    public function loginCheck()
    {
    }

    /**
     * @Route("/admin/logout", name="logout")
     */
    public function logout()
    {
    }
}
