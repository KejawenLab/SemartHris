<?php

declare(strict_types=1);

namespace KejawenLab\Application\SemartHris\Controller\Admin;

use KejawenLab\Application\SemartHris\Repository\ShiftmentRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class ShiftmentController extends AdminController
{
    /**
     * @Route("/shiftment/all", name="all_shiftment", options={"expose"=true})
     *
     * @return Response
     */
    public function findAllAction()
    {
        $companies = $this->container->get(ShiftmentRepository::class)->findAll();

        return new JsonResponse(['shiftments' => $companies]);
    }
}
