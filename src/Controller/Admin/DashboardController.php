<?php

declare(strict_types=1);

namespace KejawenLab\Application\SemartHris\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class DashboardController extends Controller
{
    /**
     * @Route("/dashboard", name="dashboard_index")
     */
    public function index()
    {
        return $this->render('dashboard/index.html.twig');
    }
}
