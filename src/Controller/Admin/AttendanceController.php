<?php

namespace KejawenLab\Application\SemartHris\Controller\Admin;

use EasyCorp\Bundle\EasyAdminBundle\Event\EasyAdminEvents;
use KejawenLab\Application\SemartHris\Component\Attendance\Model\AttendanceInterface;
use KejawenLab\Application\SemartHris\Component\Attendance\Service\AttendanceImporter;
use KejawenLab\Application\SemartHris\Component\Attendance\Service\AttendanceProcessor;
use KejawenLab\Application\SemartHris\Form\Manipulator\AttendanceManipulator;
use KejawenLab\Application\SemartHris\Repository\AttendanceRepository;
use KejawenLab\Application\SemartHris\Repository\EmployeeRepository;
use KejawenLab\Application\SemartHris\Util\Setting;
use League\Csv\Reader;
use Pagerfanta\Exception\LogicException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@kejawenlab.com>
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
        $this->initialize($request);
        $this->request = $request;

        if (null === $request->request->get('entity')) {
            return $this->redirectToRoute('easyadmin', array(
                'action' => 'list',
                'sortField' => 'attendanceDate',
                'sortDirection' => 'DESC',
                'entity' => 'Attendance',
            ));
        }

        /** @var UploadedFile $attendance */
        $attendance = $request->files->get('attendance');
        if (null === $attendance) {
            return $this->redirectToRoute('easyadmin', array(
                'action' => 'list',
                'sortField' => 'attendanceDate',
                'sortDirection' => 'DESC',
                'entity' => 'Attendance',
            ));
        }

        $destination = sprintf('%s%s%s', $this->container->getParameter('kernel.project_dir'), Setting::get(Setting::UPDATE_DESTIONATION), Setting::get(Setting::ATTENDANCE_UPLOAD_PATH));
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
        /** @var Reader $processor */
        $processor = Reader::createFromPath($request->getSession()->get(self::ATTENDANCE_UPLOAD_SESSION));
        $processor->setHeaderOffset(0);

        $importer = $this->container->get(AttendanceImporter::class);
        $importer->import($processor->getRecords());

        return $this->redirectToRoute('easyadmin', array(
            'action' => 'list',
            'sortField' => 'attendanceDate',
            'sortDirection' => 'DESC',
            'entity' => 'Attendance',
        ));
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
        $month = (int) $request->get('month', date('n'));
        $employeeRepository = $this->container->get(EmployeeRepository::class);
        if ($ids = $request->get('employees')) {
            $employees = $employeeRepository->finds($ids);
        } else {
            $employees = $employeeRepository->findAll();
        }

        $processor = $this->container->get(AttendanceProcessor::class);
        foreach ($employees as $employee) {
            $processor->process($employee, \DateTime::createFromFormat('Y-n', sprintf('%s-%s', date('Y'), $month)));
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

        $this->dispatch(EasyAdminEvents::POST_LIST, array('paginator' => $paginator));

        $output = [];
        /** @var AttendanceInterface[] $results */
        $results = $paginator->getCurrentPageResults();
        if ($results instanceof \Traversable) {
            $results = iterator_to_array($results, false);
        }

        if (!$results) {
            return $this->render($this->entity['templates']['list'], array(
                'paginator' => $paginator,
                'results' => $output,
                'fields' => $fields,
                'delete_form_template' => $this->createDeleteForm($this->entity['name'], '__id__')->createView(),
            ));
        }

        if ('DESC' === $this->request->query->get('sortDirection')) {
            try {
                $paginator->getNextPage();
                $startDate = clone $results[count($results) - 1]->getAttendanceDate();
            } catch (LogicException $exception) {
                $startDate = \DateTime::createFromFormat(Setting::get(Setting::DATE_TIME_FORMAT), sprintf('%s 00:00:00', $this->request->query->get('startDate', date(Setting::get(Setting::DATE_FORMAT)))));
            }

            $endDate = clone $results[0]->getAttendanceDate();
        } else {
            try {
                $paginator->getNextPage();
                $endDate = clone $results[count($results) - 1]->getAttendanceDate();
                $startDate = \DateTime::createFromFormat(Setting::get(Setting::DATE_TIME_FORMAT), sprintf('%s 00:00:00', $this->request->query->get('startDate', date(Setting::get(Setting::DATE_FORMAT)))));
            } catch (LogicException $exception) {
                $endDate = \DateTime::createFromFormat(Setting::get(Setting::DATE_TIME_FORMAT), sprintf('%s 00:00:00', $this->request->query->get('endDate', date(Setting::get(Setting::DATE_FORMAT)))));
                $startDate = clone $results[0]->getAttendanceDate();
            }
        }

        /** @var \DateTime $i */
        for ($i = $endDate; $i >= $startDate;) {
            $output[$i->format(Setting::get(Setting::DATE_FORMAT))] = [];
            $i->sub(new \DateInterval('P1D'));
        }

        /** @var AttendanceInterface $result */
        foreach ($results as $result) {
            if (empty($output[$result->getAttendanceDate()->format(Setting::get(Setting::DATE_FORMAT))])) {
                $output[$result->getAttendanceDate()->format(Setting::get(Setting::DATE_FORMAT))] = $result;
            }
        }

        return $this->render($this->entity['templates']['list'], array(
            'paginator' => $paginator,
            'results' => $output,
            'fields' => $fields,
            'delete_form_template' => $this->createDeleteForm($this->entity['name'], '__id__')->createView(),
        ));
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
        $startDate = \DateTime::createFromFormat(Setting::get(Setting::DATE_FORMAT), $this->request->query->get('startDate', date(Setting::get(Setting::FIRST_DATE_FORMAT))));
        $endDate = \DateTime::createFromFormat(Setting::get(Setting::DATE_FORMAT), $this->request->query->get('endDate', date(Setting::get(Setting::LAST_DATE_FORMAT))));
        $companyId = $this->request->get('company');
        $departmentId = $this->request->get('department');
        $shiftmentId = $this->request->get('shiftment');

        return $this->container->get(AttendanceRepository::class)->getFilteredAttendance($startDate, $endDate, $companyId, $departmentId, $shiftmentId, [$sortField => $sortDirection]);
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
}
