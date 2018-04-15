<?php

declare(strict_types=1);

namespace KejawenLab\Application\SemartHris\Controller\Admin;

use Doctrine\ORM\QueryBuilder;
use KejawenLab\Application\SemartHris\Component\Salary\Processor\InvalidPayrollPeriodException;
use KejawenLab\Application\SemartHris\Component\Salary\Service\PayrollProcessor;
use KejawenLab\Application\SemartHris\Component\Setting\Service\Setting;
use KejawenLab\Application\SemartHris\Component\Setting\SettingKey;
use KejawenLab\Application\SemartHris\Repository\EmployeeRepository;
use KejawenLab\Application\SemartHris\Repository\PayrollRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
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
     *
     * @throws \Exception
     */
    public function processAction(Request $request)
    {
        $this->denyAccessUnlessGranted($this->container->get(Setting::class)->get(SettingKey::SECURITY_PAYROLL_MENU));

        $employeeRepository = $this->container->get(EmployeeRepository::class);
        $employees = $employeeRepository->findByCompany($request->request->get('company', ''));
        if (empty($employees)) {
            $employees = $employeeRepository->findAll();
        }

        $month = (int) $request->request->get('month', date('n'));
        $year = (int) $request->request->get('year', date('Y'));
        $period = \DateTime::createFromFormat('Y-n', sprintf('%s-%s', $year, $month));
        if ($month > date('n')) {
            throw new InvalidPayrollPeriodException($period);
        }

        $processor = $this->container->get(PayrollProcessor::class);
        foreach ($employees as $employee) {
            if ($employee->isResign()) {
                continue;
            }

            $processor->process($employee, $period);
        }

        return new JsonResponse(['message' => 'OK']);
    }

    /**
     * @Route("/payroll/detail", name="payroll_detail")
     *
     * @param Request $request
     *
     * @return Response
     */
    public function payrollDetailAction(Request $request)
    {
        return $this->redirectToRoute('easyadmin', [
            'action' => 'list',
            'sortField' => 'createdAt',
            'sortDirection' => 'DESC',
            'entity' => 'PayrollDetail',
        ]);
    }

    /**
     * @param string $entityClass
     * @param string $sortDirection
     * @param null   $sortField
     * @param null   $dqlFilter
     *
     * @return QueryBuilder
     */
    protected function createListQueryBuilder($entityClass, $sortDirection, $sortField = null, $dqlFilter = null)
    {
        return $this->container->get(PayrollRepository::class)->createListQueryBuilder($this->request, $sortDirection, $sortField, $dqlFilter);
    }
}
