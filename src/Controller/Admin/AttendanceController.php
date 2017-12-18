<?php

declare(strict_types=1);

namespace KejawenLab\Application\SemartHris\Controller\Admin;

use EasyCorp\Bundle\EasyAdminBundle\Event\EasyAdminEvents;
use KejawenLab\Application\SemartHris\Component\Attendance\Model\AttendanceInterface;
use KejawenLab\Application\SemartHris\Component\Attendance\Service\AttendanceImporter;
use KejawenLab\Application\SemartHris\Component\Attendance\Service\AttendanceProcessor;
use KejawenLab\Application\SemartHris\Component\Attendance\Service\InvalidAttendancePeriodException;
use KejawenLab\Application\SemartHris\Component\Setting\Service\Setting;
use KejawenLab\Application\SemartHris\Component\Setting\SettingKey;
use KejawenLab\Application\SemartHris\Form\Manipulator\AttendanceManipulator;
use KejawenLab\Application\SemartHris\Repository\AttendanceRepository;
use KejawenLab\Application\SemartHris\Repository\EmployeeRepository;
use League\Csv\Reader;
use Pagerfanta\Exception\LogicException;
use Pagerfanta\Pagerfanta;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class AttendanceController extends AdminController
{
    const ATTENDANCE_UPLOAD_SESSION = 'ATTENDANCE_UPLOAD_FILE';

    /**
     * @Route("/attendance/upload", name="upload_attendance")
     * @Method("POST")
     *
     * @param Request $request
     *
     * @return Response
     */
    public function uploadAttendanceAction(Request $request): Response
    {
        $this->denyAccessUnlessGranted($this->container->get(Setting::class)->get(SettingKey::SECURITY_ATTENDANCE_MENU));

        $this->initialize($request);
        $this->request = $request;

        if (null === $request->request->get('entity')) {
            return $this->redirectToRoute('easyadmin', [
                'action' => 'list',
                'sortField' => 'attendanceDate',
                'sortDirection' => 'DESC',
                'entity' => 'Attendance',
            ]);
        }

        /** @var UploadedFile $attendance */
        $attendance = $request->files->get('attendance');
        if (null === $attendance) {
            return $this->redirectToRoute('easyadmin', [
                'action' => 'list',
                'sortField' => 'attendanceDate',
                'sortDirection' => 'DESC',
                'entity' => 'Attendance',
            ]);
        }

        $setting = $this->container->get(Setting::class);
        $destination = sprintf('%s%s%s', $this->container->getParameter('kernel.project_dir'), $setting->get(SettingKey::UPLOAD_DESTINATION), $setting->get(SettingKey::ATTENDANCE_UPLOAD_PATH));
        $fileName = sprintf('%s.%s', (new \DateTime())->format('Y_m_d_H_i_s'), $attendance->guessExtension());
        $attendance->move($destination, $fileName);

        $request->getSession()->set(self::ATTENDANCE_UPLOAD_SESSION, sprintf('%s/%s', $destination, $fileName));

        /** @var Reader $processor */
        $processor = Reader::createFromPath(sprintf('%s/%s', $destination, $fileName));
        $processor->setHeaderOffset(0);

        return $this->render('app/attendance/upload_list.html.twig', ['records' => $processor->getRecords()]);
    }

    /**
     * @Route("/attendance/process_upload", name="process_upload_attendance")
     * @Method("POST")
     *
     * @param Request $request
     *
     * @return Response
     */
    public function processUploadAction(Request $request)
    {
        $this->denyAccessUnlessGranted($this->container->get(Setting::class)->get(SettingKey::SECURITY_ATTENDANCE_MENU));

        /** @var Reader $processor */
        $processor = Reader::createFromPath($request->getSession()->get(self::ATTENDANCE_UPLOAD_SESSION));
        $processor->setHeaderOffset(0);

        $importer = $this->container->get(AttendanceImporter::class);
        $importer->import($processor->getRecords());

        return $this->redirectToRoute('easyadmin', [
            'action' => 'list',
            'sortField' => 'attendanceDate',
            'sortDirection' => 'DESC',
            'entity' => 'Attendance',
        ]);
    }

    /**
     * @Route("/attendance/process", name="process_attendance", options={"expose"=true})
     * @Method("POST")
     *
     * @param Request $request
     *
     * @return Response
     */
    public function processAction(Request $request)
    {
        $this->denyAccessUnlessGranted($this->container->get(Setting::class)->get(SettingKey::SECURITY_ATTENDANCE_MENU));

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

        $processor = $this->container->get(AttendanceProcessor::class);
        foreach ($employees as $employee) {
            $processor->process($employee, $period);
        }

        return new JsonResponse(['message' => 'OK']);
    }

    /**
     * @return Response
     */
    protected function listAction(): Response
    {
        $this->dispatch(EasyAdminEvents::PRE_LIST);

        $fields = $this->entity['list']['fields'];
        $paginator = $this->findAll($this->entity['class'], $this->request->query->get('page', 1), $this->config['list']['max_results'], $this->request->query->get('sortField'), $this->request->query->get('sortDirection'), $this->entity['list']['dql_filter']);

        $this->dispatch(EasyAdminEvents::POST_LIST, ['paginator' => $paginator]);

        $output = [];
        /** @var AttendanceInterface[] $results */
        $results = $paginator->getCurrentPageResults();
        if ($results instanceof \Traversable) {
            $results = iterator_to_array($results, false);
        }

        if (!$results) {
            return $this->doRender($paginator, $output, $fields);
        }

        $setting = $this->container->get(Setting::class);
        if ('DESC' === $this->request->query->get('sortDirection')) {
            try {
                $paginator->getNextPage();
                $startDate = clone $results[count($results) - 1]->getAttendanceDate();
            } catch (LogicException $exception) {
                $startDate = \DateTime::createFromFormat($setting->get(SettingKey::DATE_TIME_FORMAT), sprintf('%s 00:00:00', $this->request->query->get('startDate', date($setting->get(SettingKey::DATE_FORMAT)))));
            }

            $endDate = clone $results[0]->getAttendanceDate();
        } else {
            try {
                $paginator->getNextPage();
                $endDate = clone $results[count($results) - 1]->getAttendanceDate();
                $startDate = \DateTime::createFromFormat($setting->get(SettingKey::DATE_TIME_FORMAT), sprintf('%s 00:00:00', $this->request->query->get('startDate', date($setting->get(SettingKey::DATE_FORMAT)))));
            } catch (LogicException $exception) {
                $endDate = \DateTime::createFromFormat($setting->get(SettingKey::DATE_TIME_FORMAT), sprintf('%s 00:00:00', $this->request->query->get('endDate', date($setting->get(SettingKey::DATE_FORMAT)))));
                $startDate = clone $results[0]->getAttendanceDate();
            }
        }

        /** @var \DateTime $i */
        for ($i = $endDate; $i >= $startDate;) {
            $output[$i->format($setting->get(SettingKey::DATE_FORMAT))] = [];
            $i->sub(new \DateInterval('P1D'));
        }

        /** @var AttendanceInterface $result */
        foreach ($results as $result) {
            if (empty($output[$result->getAttendanceDate()->format($setting->get(SettingKey::DATE_FORMAT))])) {
                $output[$result->getAttendanceDate()->format($setting->get(SettingKey::DATE_FORMAT))] = $result;
            }
        }

        return $this->doRender($paginator, $output, $fields);
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
        $startDate = \DateTime::createFromFormat($setting->get(SettingKey::DATE_FORMAT), $this->request->query->get('startDate', date($setting->get(SettingKey::FIRST_DATE_FORMAT))));
        $endDate = \DateTime::createFromFormat($setting->get(SettingKey::DATE_FORMAT), $this->request->query->get('endDate', date($setting->get(SettingKey::LAST_DATE_FORMAT))));
        $companyId = $this->request->query->get('company');
        $departmentId = $this->request->query->get('department');
        $shiftmentId = $this->request->query->get('shiftment');
        $employeeId = $this->request->query->get('employeeId');

        return $this->container->get(AttendanceRepository::class)->getFilteredAttendance($startDate, $endDate, $companyId, $departmentId, $shiftmentId, $employeeId, [$sortField => $sortDirection]);
    }

    /**
     * @param object $entity
     * @param string $view
     *
     * @return \Symfony\Component\Form\FormBuilderInterface
     */
    protected function createEntityFormBuilder($entity, $view)
    {
        $builder = parent::createEntityFormBuilder($entity, $view);

        return $this->container->get(AttendanceManipulator::class)->manipulate($builder, $entity);
    }

    /**
     * @param Pagerfanta $paginator
     * @param array      $output
     * @param array      $fields
     *
     * @return Response
     */
    private function doRender(Pagerfanta $paginator, array $output, array $fields)
    {
        return $this->render($this->entity['templates']['list'], [
            'paginator' => $paginator,
            'results' => $output,
            'fields' => $fields,
            'delete_form_template' => $this->createDeleteForm($this->entity['name'], '__id__')->createView(),
        ]);
    }
}
