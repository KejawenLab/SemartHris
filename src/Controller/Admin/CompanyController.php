<?php

declare(strict_types=1);

namespace KejawenLab\Application\SemartHris\Controller\Admin;

use KejawenLab\Application\SemartHris\Repository\CompanyRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class CompanyController extends AdminController
{
    /**
     * @Route("/company/all", name="all_company", options={"expose"=true})
     *
     * @return Response
     */
    public function findAllAction()
    {
        $companies = $this->container->get(CompanyRepository::class)->findAll();

        return new JsonResponse(['companies' => $companies]);
    }
}
