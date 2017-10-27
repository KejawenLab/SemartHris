<?php

namespace KejawenLab\Application\SemartHris\Controller\Admin;

use EasyCorp\Bundle\EasyAdminBundle\Controller\AdminController;
use KejawenLab\Application\SemartHris\Repository\DepartmentRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@kejawenlab.com>
 */
class DepartmentController extends AdminController
{
    /**
     * @Route("/company/{id}/departments", name="department_by_company", options={"expose"=true})
     *
     * @param string $id
     *
     * @return Response
     */
    public function findByCompanyAction(string $id)
    {
        $departments = $this->container->get(DepartmentRepository::class)->findByCompany($id);

        return new JsonResponse(['departments' => $departments]);
    }
}
