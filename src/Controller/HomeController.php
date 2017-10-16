<?php

namespace KejawenLab\Application\SemartHris\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@kejawenlab.com>
 */
class HomeController extends Controller
{
    public function indexAction()
    {
        return new RedirectResponse($this->generateUrl('easyadmin'));
    }
}
