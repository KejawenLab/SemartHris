<?php

declare(strict_types=1);

namespace KejawenLab\Semart\Skeleton\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class DashboardController extends AbstractController
{
    /**
     * @Route("/", name="home_dashboard")
     */
    public function index()
    {
        return $this->render('dashboard/index.html.twig', [
            'title' => 'Dashboard Utama',
        ]);
    }
}
