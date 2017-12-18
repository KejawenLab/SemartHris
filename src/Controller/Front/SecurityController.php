<?php

declare(strict_types=1);

namespace KejawenLab\Application\SemartHris\Controller\Front;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class SecurityController extends Controller
{
    /**
     * @Route("/login", name="login")
     *
     * @Route("/login_check", name="login_check")
     *
     * @Route("/logout", name="logout")
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function loginAction()
    {
        $authUtils = $this->container->get('security.authentication_utils');

        $data = [
            'title' => $this->container->getParameter('api_platform.title'),
            'username' => $authUtils->getLastUsername(),
            'error' => $authUtils->getLastAuthenticationError(),
        ];

        return $this->render('app/user/login.html.twig', $data);
    }
}
