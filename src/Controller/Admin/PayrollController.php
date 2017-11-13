<?php

namespace KejawenLab\Application\SemartHris\Controller\Admin;

use KejawenLab\Application\SemartHris\Component\Salary\Service\PayrollProcessor;
use KejawenLab\Application\SemartHris\Repository\EmployeeRepository;
use KejawenLab\Application\SemartHris\Util\SettingUtil;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@kejawenlab.com>
 */
class PayrollController extends AdminController
{
    /**
     * @Route("/payroll/process", name="process_payroll", options={"expose"=true})
     * @Method("POST")
     *
     * @param Request $request
     *
     * @return Response
     */
    public function processAction(Request $request)
    {
        $this->denyAccessUnlessGranted(SettingUtil::get(SettingUtil::SECURITY_OVERTIME_MENU));

        $month = (int) $request->request->get('month', date('n'));
        $year = (int) $request->request->get('year', date('Y'));
        $employeeRepository = $this->container->get(EmployeeRepository::class);
        $employees = $employeeRepository->findByCompany($request->request->get('company', ''));
        if (empty($employees)) {
            $employees = $employeeRepository->findAll();
        }

        $processor = $this->container->get(PayrollProcessor::class);
        foreach ($employees as $employee) {
            if ($employee->isResign()) {
                continue;
            }

            $processor->process($employee, \DateTime::createFromFormat('Y-n', sprintf('%s-%s', $year, $month)));
        }

        return new JsonResponse(['message' => 'OK']);
    }
}
