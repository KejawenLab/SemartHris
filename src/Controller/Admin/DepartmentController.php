<?php

declare(strict_types=1);

namespace KejawenLab\Application\SemartHris\Controller\Admin;

use KejawenLab\Application\SemartHris\Repository\DepartmentRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
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
