<?php

declare(strict_types=1);

namespace KejawenLab\Application\SemartHris\Controller\Admin;

use KejawenLab\Application\SemartHris\Component\Attendance\Service\InvalidAttendancePeriodException;
use KejawenLab\Application\SemartHris\Component\Overtime\Service\OvertimeImporter;
use KejawenLab\Application\SemartHris\Component\Overtime\Service\OvertimeProcessor;
use KejawenLab\Application\SemartHris\Component\Setting\Service\Setting;
use KejawenLab\Application\SemartHris\Component\Setting\SettingKey;
use KejawenLab\Application\SemartHris\Repository\EmployeeRepository;
use KejawenLab\Application\SemartHris\Repository\OvertimeRepository;
use League\Csv\Reader;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class OvertimeController extends AdminController
{
    const OVERTIME_UPLOAD_SESSION = 'OVERTIME_UPLOAD_FILE';

    /**
     * @Route("/overtime/upload", name="upload_overtime")
     * @Method("POST")
     *
     * @param Request $request
     *
     * @return Response
     *
     * @throws
     */
    public function uploadOvertimeAction(Request $request): Response
    {
        $this->denyAccessUnlessGranted($this->container->get(Setting::class)->get(SettingKey::SECURITY_OVERTIME_MENU));

        $this->initialize($request);
        $this->request = $request;

        if (null === $request->request->get('entity')) {
            return $this->redirectToRoute('easyadmin', [
                'action' => 'list',
                'sortField' => 'overtimeDate',
                'sortDirection' => 'DESC',
                'entity' => 'Overtime',
            ]);
        }

        /** @var UploadedFile|null $overtime */
        $overtime = $request->files->get('overtime');
        if (null === $overtime) {
            return $this->redirectToRoute('easyadmin', [
                'action' => 'list',
                'sortField' => 'overtimeDate',
                'sortDirection' => 'DESC',
                'entity' => 'Overtime',
            ]);
        }

        $setting = $this->container->get(Setting::class);
        $destination = sprintf('%s%s%s', $this->container->getParameter('kernel.project_dir'), $setting->get(SettingKey::UPLOAD_DESTINATION), $setting->get(SettingKey::OVERTIME_UPLOAD_PATH));
        $fileName = sprintf('%s.%s', (new \DateTime())->format('Y_m_d_H_i_s'), $overtime->guessExtension());
        $overtime->move($destination, $fileName);

        $request->getSession()->set(self::OVERTIME_UPLOAD_SESSION, sprintf('%s/%s', $destination, $fileName));

        /** @var Reader $processor */
        $processor = Reader::createFromPath(sprintf('%s/%s', $destination, $fileName));
        $processor->setHeaderOffset(0);

        return $this->render('app/overtime/upload_list.html.twig', ['records' => $processor->getRecords()]);
    }

    /**
     * @Route("/overtime/process_upload", name="process_upload_overtime")
     * @Method("POST")
     *
     * @param Request $request
     *
     * @return Response
     *
     * @throws
     */
    public function processUploadAction(Request $request)
    {
        $this->denyAccessUnlessGranted($this->container->get(Setting::class)->get(SettingKey::SECURITY_OVERTIME_MENU));

        /** @var Reader $processor */
        $processor = Reader::createFromPath($request->getSession()->get(self::OVERTIME_UPLOAD_SESSION));
        $processor->setHeaderOffset(0);

        $importer = $this->container->get(OvertimeImporter::class);
        $importer->import($processor->getRecords());

        return $this->redirectToRoute('easyadmin', [
            'action' => 'list',
            'sortField' => 'overtimeDate',
            'sortDirection' => 'DESC',
            'entity' => 'Overtime',
        ]);
    }

    /**
     * @Route("/overtime/process", name="process_overtime", options={"expose"=true})
     * @Method("POST")
     *
     * @param Request $request
     *
     * @return Response
     */
    public function processAction(Request $request)
    {
        $this->denyAccessUnlessGranted($this->container->get(Setting::class)->get(SettingKey::SECURITY_OVERTIME_MENU));

        $month = (int) $request->request->get('month', date('n'));
        $year = (int) $request->request->get('year', date('Y'));
        $period = \DateTime::createFromFormat('Y-n', sprintf('%s-%s', $year, $month));
        if ($month > date('n')) {
            throw new InvalidAttendancePeriodException($period);
        }

        $employeeRepository = $this->container->get(EmployeeRepository::class);
        if ($ids = $request->request->get('employees')) {
            $employees = $employeeRepository->finds($ids);
        } else {
            $employees = $employeeRepository->findAll();
        }

        $processor = $this->container->get(OvertimeProcessor::class);
        foreach ($employees as $employee) {
            $processor->process($employee, $period);
        }

        return new JsonResponse(['message' => 'OK']);
    }

    /**
     * @param string $entityClass
     * @param string $sortDirection
     * @param null   $sortField
     * @param null   $dqlFilter
     *
     * @return array
     */
    protected function createListQueryBuilder($entityClass, $sortDirection, $sortField = null, $dqlFilter = null)
    {
        $setting = $this->container->get(Setting::class);
        $startDate = $this->request->query->get('startDate', null);
        if (!$startDate) {
            $startDate = date($setting->get(SettingKey::FIRST_DATE_FORMAT));
        }

        $endDate = $this->request->query->get('endDate', null);
        if (!$endDate) {
            $endDate = date($setting->get(SettingKey::FIRST_DATE_FORMAT));
        }

        $startDate = \DateTime::createFromFormat($setting->get(SettingKey::DATE_FORMAT), $startDate);
        $endDate = \DateTime::createFromFormat($setting->get(SettingKey::DATE_FORMAT), $endDate);
        $companyId = $this->request->query->get('company');
        $departmentId = $this->request->query->get('department');
        $shiftmentId = $this->request->query->get('shiftment');
        $employeeId = $this->request->query->get('employeeId');

        return $this->container->get(OvertimeRepository::class)->getFilteredOvertime($startDate, $endDate, $companyId, $departmentId, $shiftmentId, $employeeId, [$sortField => $sortDirection]);
    }
}
