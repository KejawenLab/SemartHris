<?php

namespace KejawenLab\Application\SemartHris\Controller\Front;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@kejawenlab.com>
 */
class SecurityController extends Controller
{
    /**
     * @Route("/login", name="login")
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

    /**
     * @Route("/login_check", name="login_check")
     */
    public function checkAction()
    {
        throw new \RuntimeException('You must configure the check path to be handled by the firewall using form_login in your security firewall configuration.');
    }

    /**
     * @Route("/logout", name="logout")
     */
    public function logoutAction()
    {
        throw new \RuntimeException('You must activate the logout in your security firewall configuration.');
    }
}
