<?php

declare(strict_types=1);

namespace KejawenLab\Application\SemartHris\Controller\Admin;

use KejawenLab\Application\SemartHris\Component\Salary\StateType;
use KejawenLab\Application\SemartHris\Component\Setting\Service\Setting;
use KejawenLab\Application\SemartHris\Component\Setting\SettingKey;
use KejawenLab\Application\SemartHris\Repository\SalaryComponentRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class SalaryComponentController extends AdminController
{
    /**
     * @Route("/salary-component", name="salary_component", options={"expose"=true})
     * @Method("GET")
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function findByStateAction(Request $request)
    {
        $this->denyAccessUnlessGranted($this->container->get(Setting::class)->get(SettingKey::SECURITY_PAYROLL_MENU));

        return new JsonResponse(['components' => $this->container->get(SalaryComponentRepository::class)->findByState($request->query->get('state', StateType::STATE_PLUS))]);
    }

    /**
     * @Route("/salary-component/fixed", name="fixed_component", options={"expose"=true})
     * @Method("GET")
     *
     * @return Response
     */
    public function findFixedAction()
    {
        $this->denyAccessUnlessGranted($this->container->get(Setting::class)->get(SettingKey::SECURITY_PAYROLL_MENU));

        return new JsonResponse(['components' => $this->container->get(SalaryComponentRepository::class)->findFixed()]);
    }
}
